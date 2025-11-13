<?php

namespace App\Http\Controllers\Karyawan\Kehadiran;

use App\Http\Controllers\Controller;
use App\Models\LogPotongGaji;
use App\Models\PotongGaji;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPotongGajiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = PotongGaji::join('user as u1', 'potong_gaji.user_id', '=', 'u1.id')
                ->join('user as u2', 'potong_gaji.atasan_id', '=', 'u2.id')
                ->join('user as u3', 'potong_gaji.pj_id', '=', 'u3.id')
                ->select([
                    'potong_gaji.id as cuti_id',
                    'potong_gaji.user_id',
                    'potong_gaji.tgl_pengajuan',
                    'potong_gaji.tgl_mulai',
                    'potong_gaji.tgl_selesai',
                    'potong_gaji.tgl_masuk',
                    'potong_gaji.lama_cuti',
                    'potong_gaji.status',
                    'potong_gaji.tahun',
                    'potong_gaji.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                    'u3.name as nama_pj',
                ])
                ->where('potong_gaji.user_id', $users->id)
                ->where('potong_gaji.is_deleted', '1')
                ->orderBy('potong_gaji.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('potong_gaji.tgl_mulai', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%")
                        ->where('u2.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('potong_gaji.status', $request->status);
            }

            // Hitung jumlah per status
            $statusCounts = [
                '0' => (clone $query)->where('potong_gaji.status', '0')->count(),
                '1' => (clone $query)->where('potong_gaji.status', '1')->count(),
                '2' => (clone $query)->where('potong_gaji.status', '2')->count(),
                '3' => (clone $query)->where('potong_gaji.status', '3')->count(),
                '4' => (clone $query)->where('potong_gaji.status', '4')->count(),
            ];

            $totalPotongs = PotongGaji::where('user_id', $users->id)
                ->where('is_deleted', '1')
                ->where('status', '4')
                ->whereBetween('tgl_mulai', [$request->start_date, $request->end_date])
                ->count();

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->cuti_id ?? '';
                $editUrl = route("karyawan-potonggaji.edit", $item->cuti_id ?? '');

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
                'totalPotongs' => $totalPotongs,
            ]);
        }

        return view('karyawan.kehadiran.potong-gaji.index');
    }

    public function create()
    {
        return view('karyawan.kehadiran.potong-gaji.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atasan_id' => 'required',
            'pj_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tgl_masuk' => 'required|date|after_or_equal:tgl_selesai',
        ], [
            // Pesan error umum
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after_or_equal' => 'Tanggal :attribute harus sama atau setelah :other.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
                'file' => 'Ukuran file :attribute maksimal :max kilobyte.',
            ],
            'mimes' => 'File :attribute harus berupa file dengan format: jpg, jpeg, png, atau pdf.',

            // Pesan error khusus setiap field
            'atasan_id.required' => 'Atasan langsung harus dipilih.',
            'pj_id.required' => 'Penanggung jawab harus dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai cuti wajib diisi.',
            'tgl_selesai.required' => 'Tanggal selesai cuti wajib diisi.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'tgl_masuk.required' => 'Tanggal masuk kerja kembali wajib diisi.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        // Hitung jumlah hari cuti
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahCuti = $tglSelesai->diffInDays($tglMulai) + 1;

        $cutis = PotongGaji::create([
            'user_id' => $users->id,
            'atasan_id' => $request->atasan_id,
            'pj_id' => $request->pj_id,
            'tgl_pengajuan' => $carbons,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $jumlahCuti ?? '0',
            'status' => '1',
            'tahun' => $carbons->format('Y'),
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogPotongGaji::create([
            'potong_gaji_id' => $cutis->id,
            'aktivitas' => 'Membuat Data Potong Gaji',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('karyawan-potonggaji.index')->with('success', 'Selamat ! Anda berhasil membuat Data Potong Gaji');
    }

    public function edit($id)
    {
        $cutis = PotongGaji::join('user as u1', 'potong_gaji.user_id', '=', 'u1.id')
            ->join('user as u2', 'potong_gaji.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'potong_gaji.pj_id', '=', 'u3.id')
            ->select([
                'potong_gaji.id as cuti_id',
                'potong_gaji.user_id',
                'potong_gaji.atasan_id',
                'potong_gaji.pj_id',
                'potong_gaji.tgl_pengajuan',
                'potong_gaji.tgl_mulai',
                'potong_gaji.tgl_selesai',
                'potong_gaji.tgl_masuk',
                'potong_gaji.lama_cuti',
                'potong_gaji.status',
                'potong_gaji.tahun',
                'potong_gaji.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
                'u3.name as nama_pj',
            ])
            ->where('potong_gaji.id', $id)
            ->where('potong_gaji.is_deleted', '1')
            ->orderBy('potong_gaji.id', 'desc')
            ->first();
        return view('karyawan.kehadiran.potong-gaji.edit', [
            'cutis' => $cutis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atasan_id' => 'required',
            'pj_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tgl_masuk' => 'required|date|after_or_equal:tgl_selesai',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after_or_equal' => 'Tanggal :attribute harus sama atau setelah :other.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
                'file' => 'Ukuran file :attribute maksimal :max kilobyte.',
            ],
            'atasan_id.required' => 'Atasan langsung harus dipilih.',
            'pj_id.required' => 'Penanggung jawab harus dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai cuti wajib diisi.',
            'tgl_selesai.required' => 'Tanggal selesai cuti wajib diisi.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'tgl_masuk.required' => 'Tanggal masuk kerja kembali wajib diisi.',
        ]);

        $users = Auth::user();
        $now = Carbon::now();

        $cuti = PotongGaji::where('id', $id)->where('is_deleted', '1')->firstOrFail();

        // Hitung lama cuti baru
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $lamaCutiBaru = $tglSelesai->diffInDays($tglMulai) + 1;

        $cuti->update([
            'atasan_id' => $request->atasan_id,
            'pj_id' => $request->pj_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $lamaCutiBaru,
            'tahun' => $now->year,
            'updated_at' => $now,
            'updated_by' => $users->name,
        ]);

        LogPotongGaji::create([
            'potong_gaji_id' => $cuti->id,
            'aktivitas' => 'Memperbaharui Data Potong Gaji',
            'user' => $users->name,
            'tanggal' => $now->toDateString(),
            'waktu_dibuat' => $now,
        ]);

        return redirect()->route('karyawan-potonggaji.index')
            ->with('success', 'Data Potong Gaji berhasil diperbaharui.');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $now = Carbon::now();

        // Ambil data cuti
        $cuti = PotongGaji::where('id', $id)
            ->where('is_deleted', '1')
            ->firstOrFail();

        // Update is_deleted menjadi 0 (soft delete)
        $cuti->update([
            'is_deleted' => '0',
            'deleted_at' => $now,
            'deleted_by' => $users->name,
        ]);

        // Simpan log aktivitas
        LogPotongGaji::create([
            'potong_gaji_id' => $cuti->id,
            'aktivitas' => 'Menghapus Data Potong Gaji',
            'user' => $users->name,
            'tanggal' => $now->toDateString(),
            'waktu_dibuat' => $now,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Data pengajuan cuti potong gaji berhasil di hapus.',
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
