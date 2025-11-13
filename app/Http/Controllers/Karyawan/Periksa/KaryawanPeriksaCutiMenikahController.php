<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\CutiMenikah;
use App\Models\LogCutiMenikah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaCutiMenikahController extends Controller
{
     public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = CutiMenikah::join('user as u1', 'cuti_menikah.user_id', '=', 'u1.id')
                ->join('user as u2', 'cuti_menikah.atasan_id', '=', 'u2.id')
                ->select([
                    'cuti_menikah.id as cuti_id',
                    'cuti_menikah.user_id',
                    'cuti_menikah.atasan_id',
                    'cuti_menikah.tgl_mulai',
                    'cuti_menikah.tgl_selesai',
                    'cuti_menikah.tgl_masuk',
                    'cuti_menikah.lama_cuti',
                    'cuti_menikah.alasan',
                    'cuti_menikah.status',
                    'cuti_menikah.bukti_menikah',
                    'cuti_menikah.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('cuti_menikah.atasan_id', $users->id)
                ->where('cuti_menikah.status', '1')
                ->where('cuti_menikah.is_deleted', '1')
                ->orderBy('cuti_menikah.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('cuti_menikah.tgl_mulai', [$start_date, $end_date]);
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('cuti_menikah.status', $request->status);
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

        return view('karyawan.periksa-kehadiran.cuti-menikah.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = CutiMenikah::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $id,
            'aktivitas' => 'Menyetujui cuti menikah',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui cuti menikah karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = CutiMenikah::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $id,
            'aktivitas' => 'Menolak Cuti Menikah',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak cuti menikah karyawan',
        ]);
    }
}
