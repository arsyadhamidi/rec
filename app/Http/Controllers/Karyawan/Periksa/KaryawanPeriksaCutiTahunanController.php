<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\CutiTahunan;
use App\Models\LogCutiTahunan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaCutiTahunanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = CutiTahunan::join('user as u1', 'cuti_tahunan.user_id', '=', 'u1.id')
                ->join('user as u2', 'cuti_tahunan.atasan_id', '=', 'u2.id')
                ->join('user as u3', 'cuti_tahunan.pj_id', '=', 'u3.id')
                ->select([
                    'cuti_tahunan.id as cuti_id',
                    'cuti_tahunan.atasan_id',
                    'cuti_tahunan.tgl_pengajuan',
                    'cuti_tahunan.tgl_mulai',
                    'cuti_tahunan.tgl_selesai',
                    'cuti_tahunan.tgl_masuk',
                    'cuti_tahunan.lama_cuti',
                    'cuti_tahunan.status',
                    'cuti_tahunan.ket_status',
                    'cuti_tahunan.tahun',
                    'cuti_tahunan.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                    'u3.name as nama_pj',
                ])
                ->where('cuti_tahunan.status', '1')
                ->where('cuti_tahunan.atasan_id', $users->id)
                ->where('cuti_tahunan.is_deleted', '1')
                ->orderBy('cuti_tahunan.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('cuti_tahunan.tgl_mulai', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%")
                        ->where('u2.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('cuti_tahunan.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $diterima = $item->cuti_id ?? '';
                $ditolak = $item->cuti_id ?? '';

                $item->aksi = '
        <button type="button"
                class="btn btn-outline-success btn-diterima"
                data-diterima="' . e($diterima) . '">
            <i class="fas fa-check"></i>
        </button>
        <button type="button"
                class="btn btn-outline-danger btn-ditolak"
                data-ditolak="' . e($ditolak) . '">
            <i class="fas fa-times"></i>
        </button>
    ';

                return $item;
            });


            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $dataWithActions,
            ]);
        }

        return view('karyawan.periksa-kehadiran.cuti-tahunan.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiTahunan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiTahunan::create([
            'cuti_tahunan_id' => $id,
            'aktivitas' => 'Menyetujui Cuti Tahunan karyawan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui cuti tahunan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiTahunan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiTahunan::create([
            'cuti_tahunan_id' => $id,
            'aktivitas' => 'Menolak Cuti Tahunan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak cuti tahunan karyawan',
        ]);
    }
}
