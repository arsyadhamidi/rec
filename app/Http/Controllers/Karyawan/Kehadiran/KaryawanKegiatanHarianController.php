<?php

namespace App\Http\Controllers\Karyawan\Kehadiran;

use App\Http\Controllers\Controller;
use App\Models\KegiatanHarian;
use App\Models\LogKegiatanHarian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanKegiatanHarianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = KegiatanHarian::join('user as u1', 'kegiatan_harian.user_id', '=', 'u1.id')
                ->join('user as u2', 'kegiatan_harian.atasan_id', '=', 'u2.id')
                ->select([
                    'kegiatan_harian.id as kegiatan_id',
                    'kegiatan_harian.user_id',
                    'kegiatan_harian.tgl_kegiatan',
                    'kegiatan_harian.keterangan',
                    'kegiatan_harian.status',
                    'kegiatan_harian.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('kegiatan_harian.user_id', $users->id)
                ->where('kegiatan_harian.is_deleted', '1')
                ->orderBy('kegiatan_harian.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('kegiatan_harian.tgl_kegiatan', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%")
                        ->where('u2.name', 'LIKE', "%{$search}%")
                        ->where('kegiatan_harian.keterangan', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('kegiatan_harian.status', $request->status);
            }

            // Hitung jumlah per status
            $statusCounts = [
                '0' => (clone $query)->where('kegiatan_harian.status', '0')->count(),
                '1' => (clone $query)->where('kegiatan_harian.status', '1')->count(),
                '2' => (clone $query)->where('kegiatan_harian.status', '2')->count(),
                '3' => (clone $query)->where('kegiatan_harian.status', '3')->count(),
                '4' => (clone $query)->where('kegiatan_harian.status', '4')->count(),
            ];

            $totalKegiatans = KegiatanHarian::where('user_id', $users->id)
                ->where('is_deleted', '1')
                ->whereBetween('tgl_kegiatan', [$request->start_date, $request->end_date])
                ->count();

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->kegiatan_id ?? '';

                // Cek status
                if (($item->status ?? 0) == 1) {
                    $editButton = '
            <a href="' . route("karyawan-kegiatanharian.edit", $item->kegiatan_id ?? '') . '" class="btn btn-outline-primary me-1">
                <i class="fas fa-edit"></i>
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
                    $deleteButton = '';
                }

                $item->aksi = $editButton . $deleteButton;

                return $item;
            });

            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $dataWithActions,
                'statusCounts' => $statusCounts,
                'totalKegiatanAlls' => $totalKegiatans,
            ]);
        }

        return view('karyawan.kehadiran.kegiatan-harian.index');
    }

    public function create()
    {
        return view('karyawan.kehadiran.kegiatan-harian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_kegiatan' => 'required',
            'keterangan' => 'required|max:100',
        ], [
            'atasan_id.required' => 'Atasan wajib dipilih.',
            'tgl_kegiatan.required' => 'Tanggal kegiatan tidak boleh kosong.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'keterangan.max' => 'Keterangan maksimal 100 karakter.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $kegiatans = KegiatanHarian::create([
            'user_id' => $users->id,
            'atasan_id' => $request->atasan_id,
            'tgl_kegiatan' => $request->tgl_kegiatan,
            'status' => '1',
            'keterangan' => $request->keterangan,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogKegiatanHarian::create([
            'kegiatan_harian_id' => $kegiatans->id,
            'aktivitas' => 'Membuat Data Kegiatan Harian',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-kegiatanharian.index')->with('success', 'Selamat ! Anda berhasil membuat data kegiatan harian!');
    }

    public function edit($id)
    {
        $kegiatans = KegiatanHarian::join('user as u1', 'kegiatan_harian.user_id', '=', 'u1.id')
            ->join('user as u2', 'kegiatan_harian.atasan_id', '=', 'u2.id')
            ->select([
                'kegiatan_harian.id as kegiatan_id',
                'kegiatan_harian.user_id',
                'kegiatan_harian.atasan_id',
                'kegiatan_harian.tgl_kegiatan',
                'kegiatan_harian.keterangan',
                'kegiatan_harian.status',
                'kegiatan_harian.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('kegiatan_harian.id', $id)
            ->where('kegiatan_harian.is_deleted', '1')
            ->orderBy('kegiatan_harian.id', 'desc')
            ->first();
        return view('karyawan.kehadiran.kegiatan-harian.edit', [
            'kegiatans' => $kegiatans
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_kegiatan' => 'required',
            'keterangan' => 'required|max:100',
        ], [
            'atasan_id.required' => 'Atasan wajib dipilih.',
            'tgl_kegiatan.required' => 'Tanggal kegiatan tidak boleh kosong.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'keterangan.max' => 'Keterangan maksimal 100 karakter.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $kegiatans = KegiatanHarian::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $kegiatans->update([
            'atasan_id' => $request->atasan_id,
            'tgl_kegiatan' => $request->tgl_kegiatan,
            'keterangan' => $request->keterangan,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogKegiatanHarian::create([
            'kegiatan_harian_id' => $kegiatans->id,
            'aktivitas' => 'Memperbaharui Data Kegiatan Harian',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-kegiatanharian.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data kegiatan harian!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();

        $kegiatans = KegiatanHarian::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $kegiatans->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0',
        ]);

        LogKegiatanHarian::create([
            'kegiatan_harian_id' => $kegiatans->id,
            'aktivitas' => 'Menghapus Data Kegiatan Harian',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil memperbaharui data kegiatan harian!',
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
