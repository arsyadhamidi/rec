<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\CutiMelahirkan;
use App\Models\LogCutiMelahirkan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaCutiMelahirkanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = CutiMelahirkan::join('user as u1', 'cuti_melahirkan.user_id', '=', 'u1.id')
                ->join('user as u2', 'cuti_melahirkan.atasan_id', '=', 'u2.id')
                ->select([
                    'cuti_melahirkan.id as cuti_id',
                    'cuti_melahirkan.user_id',
                    'cuti_melahirkan.atasan_id',
                    'cuti_melahirkan.tgl_mulai',
                    'cuti_melahirkan.tgl_selesai',
                    'cuti_melahirkan.tgl_masuk',
                    'cuti_melahirkan.lama_cuti',
                    'cuti_melahirkan.alasan',
                    'cuti_melahirkan.status',
                    'cuti_melahirkan.bukti_melahirkan',
                    'cuti_melahirkan.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('cuti_melahirkan.status', '1')
                ->where('cuti_melahirkan.atasan_id', $users->id)
                ->where('cuti_melahirkan.is_deleted', '1')
                ->orderBy('cuti_melahirkan.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('cuti_melahirkan.tgl_mulai', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('cuti_melahirkan.status', $request->status);
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

        return view('karyawan.periksa-kehadiran.cuti-melahirkan.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMelahirkan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMelahirkan::create([
            'cuti_melahirkan_id' => $id,
            'aktivitas' => 'Menyetujui Cuti Melahirkan karyawan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui Cuti Melahirkan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMelahirkan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMelahirkan::create([
            'cuti_melahirkan_id' => $id,
            'aktivitas' => 'Menolak Cuti Melahirkan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak cuti melahirkan',
        ]);
    }
}
