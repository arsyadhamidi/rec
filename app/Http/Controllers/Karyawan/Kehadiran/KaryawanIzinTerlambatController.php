<?php

namespace App\Http\Controllers\Karyawan\Kehadiran;

use App\Http\Controllers\Controller;
use App\Models\IzinTerlambat;
use App\Models\LogIzinTerlambat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanIzinTerlambatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = IzinTerlambat::join('user as u1', 'izin_terlambat.user_id', '=', 'u1.id')
                ->join('user as u2', 'izin_terlambat.atasan_id', '=', 'u2.id')
                ->select([
                    'izin_terlambat.id as izin_id',
                    'izin_terlambat.tgl_izin',
                    'izin_terlambat.jam_datang',
                    'izin_terlambat.alasan',
                    'izin_terlambat.status',
                    'izin_terlambat.bukti_terlambat',
                    'izin_terlambat.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('izin_terlambat.user_id', $users->id)
                ->where('izin_terlambat.is_deleted', '1')
                ->orderBy('izin_terlambat.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('izin_terlambat.tgl_izin', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('izin_terlambat.status', $request->status);
            }

            // Hitung jumlah per status
            $statusCounts = [
                '0' => (clone $query)->where('izin_terlambat.status', '0')->count(),
                '1' => (clone $query)->where('izin_terlambat.status', '1')->count(),
                '2' => (clone $query)->where('izin_terlambat.status', '2')->count(),
                '3' => (clone $query)->where('izin_terlambat.status', '3')->count(),
                '4' => (clone $query)->where('izin_terlambat.status', '4')->count(),
            ];

            $totalIzins = IzinTerlambat::where('user_id', $users->id)
                ->where('is_deleted', '1')
                ->where('status', '4')
                ->whereBetween('tgl_izin', [$request->start_date, $request->end_date])
                ->count();

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->izin_id ?? '';
                $editUrl = route("karyawan-izinterlambat.edit", $item->izin_id ?? '');
                $imageUrl = asset('storage/' . $item->bukti_terlambat);

                // Cek status
                if (($item->status ?? 0) == 1) {
                    $editButton = '
            <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
                <i class="fas fa-edit"></i>
            </a>
        ';
                    $viewImageButton = '
            <a href="' . $imageUrl . '" class="btn btn-outline-warning mx-1" target="_blank">
                <i class="fas fa-image"></i>
            </a>
        ';
                    $deleteButton = '
            <button type="button"
                    class="btn btn-outline-danger btn-delete"
                    data-resultid="' . e($resultid) . '">
                <i class="fas fa-trash-alt"></i>
            </button>
        ';
                } else {
                    $editButton = '<span class="badge badge-secondary">Tidak Bisa Diperbaharui</span>';
                    $viewImageButton = '';
                    $deleteButton = '';
                }

                $item->aksi = $editButton . $viewImageButton . $deleteButton;

                return $item;
            });


            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $dataWithActions,
                'statusCounts' => $statusCounts,
                'totalIzins' => $totalIzins,
            ]);
        }

        return view('karyawan.kehadiran.izin-terlambat.index');
    }

    public function create()
    {
        return view('karyawan.kehadiran.izin-terlambat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_izin' => 'required|date',
            'jam_datang' => 'required',
            'alasan' => 'required|max:500',
            'bukti_terlambat' => 'required|mimes:png,jpg,jpeg|max:10248'
        ], [
            'atasan_id.required' => 'Nama Atasan harus dipilih.',
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date' => 'Format tanggal izin tidak valid.',
            'jam_datang.required' => 'Jam datang wajib diisi.',
            'alasan.required' => 'Alasan keterlambatan wajib diisi.',
            'alasan.max' => 'Alasan maksimal 500 karakter.',
            'bukti_terlambat.required' => 'Bukti foto keterlambatan wajib diunggah.',
            'bukti_terlambat.mimes' => 'Bukti hanya boleh berupa gambar dengan format PNG, JPG, atau JPEG.',
            'bukti_terlambat.max' => 'Ukuran file bukti maksimal 10 MB.'
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $buktiTerlambat = null;
        if ($request->file('bukti_terlambat')) {
            $buktiTerlambat = $request->file('bukti_terlambat')->store('bukti_terlambat');
        }

        $izins = IzinTerlambat::create([
            'user_id' => $users->id,
            'atasan_id' => $request->atasan_id,
            'tgl_izin' => $request->tgl_izin,
            'jam_datang' => $request->jam_datang,
            'alasan' => $request->alasan,
            'status' => '1',
            'bukti_terlambat' => $buktiTerlambat,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1',
        ]);

        LogIzinTerlambat::create([
            'izin_terlambat_id' => $izins->id,
            'aktivitas' => 'Membuat Data Izin Terlambat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-izinterlambat.index')->with('success', 'Selamat ! Anda berhasil membuat data izin terlambat!');
    }

    public function edit($id)
    {
        $izins = IzinTerlambat::join('user as u1', 'izin_terlambat.user_id', '=', 'u1.id')
            ->join('user as u2', 'izin_terlambat.atasan_id', '=', 'u2.id')
            ->select([
                'izin_terlambat.id as izin_id',
                'izin_terlambat.user_id',
                'izin_terlambat.atasan_id',
                'izin_terlambat.tgl_izin',
                'izin_terlambat.jam_datang',
                'izin_terlambat.alasan',
                'izin_terlambat.status',
                'izin_terlambat.bukti_terlambat',
                'izin_terlambat.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('izin_terlambat.is_deleted', '1')
            ->where('izin_terlambat.id', $id)
            ->orderBy('izin_terlambat.id', 'desc')
            ->first();

        return view('karyawan.kehadiran.izin-terlambat.edit', [
            'izins' => $izins,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_izin' => 'required|date',
            'jam_datang' => 'required',
            'alasan' => 'required|max:500',
            'bukti_terlambat' => 'mimes:png,jpg,jpeg|max:10248'
        ], [
            'atasan_id.required' => 'Nama Atasan harus dipilih.',
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date' => 'Format tanggal izin tidak valid.',
            'jam_datang.required' => 'Jam datang wajib diisi.',
            'alasan.required' => 'Alasan keterlambatan wajib diisi.',
            'alasan.max' => 'Alasan maksimal 500 karakter.',
            'bukti_terlambat.mimes' => 'Bukti hanya boleh berupa gambar dengan format PNG, JPG, atau JPEG.',
            'bukti_terlambat.max' => 'Ukuran file bukti maksimal 10 MB.'
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $izins = IzinTerlambat::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $buktiTerlambat = null;
        if ($request->file('bukti_terlambat')) {
            if ($izins->bukti_terlambat) {
                Storage::delete($izins->bukti_terlambat);
            }
            $buktiTerlambat = $request->file('bukti_terlambat')->store('bukti_terlambat');
        } else {
            $buktiTerlambat = $izins->bukti_terlambat;
        }

        $izins->update([
            'atasan_id' => $request->atasan_id,
            'tgl_izin' => $request->tgl_izin,
            'jam_datang' => $request->jam_datang,
            'alasan' => $request->alasan,
            'bukti_terlambat' => $buktiTerlambat,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogIzinTerlambat::create([
            'izin_terlambat_id' => $izins->id,
            'aktivitas' => 'Memperbaharui Data Izin Terlambat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-izinterlambat.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data izin terlambat!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();

        $izins = IzinTerlambat::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $izins->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogIzinTerlambat::create([
            'izin_terlambat_id' => $izins->id,
            'aktivitas' => 'Menghapus Data Izin Terlambat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data izin terlambat!',
        ]);
    }

    public function getUser(Request $request)
    {
        $id = $request->get('id');
        $searchTerm = $request->get('q');

        $query = User::select([
            'user.id',
            'user.name',
            'user.is_deleted',
        ]);

        if ($id) {
            $item = $query->where('id', $id)->first();
            return response()->json(['item' => $item]);
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('user.id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('user.name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $results = $query->where('is_deleted', '1')->get();
        return response()->json(['items' => $results]);
    }
}
