<?php

namespace App\Http\Controllers\Karyawan\Kehadiran;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\LogIzinKeluar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanIzinKeluarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = IzinKeluar::join('user as u1', 'izin_keluar.user_id', '=', 'u1.id')
                ->join('user as u2', 'izin_keluar.atasan_id', '=', 'u2.id')
                ->select([
                    'izin_keluar.id as izin_id',
                    'izin_keluar.user_id',
                    'izin_keluar.atasan_id',
                    'izin_keluar.tgl_izin',
                    'izin_keluar.jam_keluar',
                    'izin_keluar.jam_kembali',
                    'izin_keluar.keperluan',
                    'izin_keluar.status',
                    'izin_keluar.tahun',
                    'izin_keluar.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('izin_keluar.user_id', $users->id)
                ->where('izin_keluar.is_deleted', '1')
                ->orderBy('izin_keluar.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('izin_keluar.tgl_izin', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('izin_keluar.status', $request->status);
            }

            // Hitung jumlah per status
            $statusCounts = [
                '0' => (clone $query)->where('izin_keluar.status', '0')->count(),
                '1' => (clone $query)->where('izin_keluar.status', '1')->count(),
                '2' => (clone $query)->where('izin_keluar.status', '2')->count(),
                '3' => (clone $query)->where('izin_keluar.status', '3')->count(),
                '4' => (clone $query)->where('izin_keluar.status', '4')->count(),
            ];

            $totalIzins = IzinKeluar::where('user_id', $users->id)
                ->where('is_deleted', '1')
                ->where('status', '4')
                ->whereBetween('tgl_izin', [$request->start_date, $request->end_date])
                ->count();

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->izin_id ?? '';
                $editUrl = route("karyawan-izinkeluar.edit", $item->izin_id ?? '');

                // Cek status
                if (($item->status ?? 0) == 1) {
                    $editButton = '
            <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
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
                'totalIzins' => $totalIzins,
            ]);
        }

        return view('karyawan.kehadiran.izin-keluar.index');
    }

    public function create()
    {
        return view('karyawan.kehadiran.izin-keluar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_izin' => 'required|date',
            'jam_keluar' => 'required',
            'jam_kembali' => 'required|after:jam_keluar',
            'keperluan' => 'required|max:500',
        ], [
            // 🔹 Pesan error umum
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after' => 'Jam kembali harus setelah jam mulai.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
            ],

            // 🔹 Pesan error khusus setiap field
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date' => 'Tanggal izin harus berupa tanggal yang valid.',
            'jam_keluar.required' => 'Jam keluar keluar wajib diisi.',
            'jam_kembali.required' => 'Jam kembali wajib diisi.',
            'jam_kembali.after' => 'Jam kembali harus lebih besar dari jam keluar.',
            'keperluan.required' => 'Keperluan izin wajib diisi.',
            'keperluan.max' => 'Keperluan izin maksimal 500 karakter.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $izins = IzinKeluar::create([
            'user_id' => $users->id,
            'atasan_id' => $request->atasan_id,
            'tgl_izin' => $request->tgl_izin,
            'jam_keluar' => $request->jam_keluar,
            'jam_kembali' => $request->jam_kembali,
            'keperluan' => $request->keperluan,
            'status' => '1',
            'tahun' => $carbons->format('Y'),
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogIzinKeluar::create([
            'izin_keluar_id' => $izins->id,
            'aktivitas' => 'Membuat Data Izin Keluar Jam Dinas',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-izinkeluar.index')->with('success', 'Selamat ! Anda berhasil menambahkan data izin keluar dari jam dinas!');
    }

    public function edit($id)
    {
        $izins = IzinKeluar::join('user as u1', 'izin_keluar.user_id', '=', 'u1.id')
            ->join('user as u2', 'izin_keluar.atasan_id', '=', 'u2.id')
            ->select([
                'izin_keluar.id as izin_id',
                'izin_keluar.user_id',
                'izin_keluar.atasan_id',
                'izin_keluar.tgl_izin',
                'izin_keluar.jam_keluar',
                'izin_keluar.jam_kembali',
                'izin_keluar.keperluan',
                'izin_keluar.status',
                'izin_keluar.tahun',
                'izin_keluar.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('izin_keluar.id', $id)
            ->where('izin_keluar.is_deleted', '1')
            ->orderBy('izin_keluar.id', 'desc')
            ->first();
        return view('karyawan.kehadiran.izin-keluar.edit', [
            'izins' => $izins,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_izin' => 'required|date',
            'jam_keluar' => 'required',
            'jam_kembali' => 'required|after:jam_keluar',
            'keperluan' => 'required|max:500',
        ], [
            // 🔹 Pesan error umum
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after' => 'Jam kembali harus setelah jam mulai.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
            ],

            // 🔹 Pesan error khusus setiap field
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date' => 'Tanggal izin harus berupa tanggal yang valid.',
            'jam_keluar.required' => 'Jam keluar keluar wajib diisi.',
            'jam_kembali.required' => 'Jam kembali wajib diisi.',
            'jam_kembali.after' => 'Jam kembali harus lebih besar dari jam keluar.',
            'keperluan.required' => 'Keperluan izin wajib diisi.',
            'keperluan.max' => 'Keperluan izin maksimal 500 karakter.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = IzinKeluar::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'atasan_id' => $request->atasan_id,
            'tgl_izin' => $request->tgl_izin,
            'jam_keluar' => $request->jam_keluar,
            'jam_kembali' => $request->jam_kembali,
            'keperluan' => $request->keperluan,
            'tahun' => $carbons->format('Y'),
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogIzinKeluar::create([
            'izin_keluar_id' => $id,
            'aktivitas' => 'Memperbaharui Data Izin Keluar Jam Dinas',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-izinkeluar.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data izin keluar dari jam dinas!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = IzinKeluar::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogIzinKeluar::create([
            'izin_keluar_id' => $id,
            'aktivitas' => 'Menghapus Data Cuti Melahirkan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data izin keluar dari jam dinas!',
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
