<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\LogPotongGaji;
use App\Models\PotongGaji;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaPotongGajiController extends Controller
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
                    'potong_gaji.atasan_id',
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
                ->where('potong_gaji.atasan_id', $users->id)
                ->where('potong_gaji.status', '1')
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

        return view('karyawan.periksa-kehadiran.potong-gaji.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = PotongGaji::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogPotongGaji::create([
            'potong_gaji_id' => $id,
            'aktivitas' => 'Menyetujui Cuti Potong Gaji',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui cuti potong gaji karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = PotongGaji::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogPotongGaji::create([
            'potong_gaji_id' => $id,
            'aktivitas' => 'Menolak Cuti Potong Gaji',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak cuti potong gaji karyawan',
        ]);
    }
}
