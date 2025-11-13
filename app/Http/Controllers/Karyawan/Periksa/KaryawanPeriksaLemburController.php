<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\Lembur;
use App\Models\LogLembur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaLemburController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = Lembur::join('user as u1', 'lembur.user_id', '=', 'u1.id')
                ->join('user as u2', 'lembur.atasan_id', '=', 'u2.id')
                ->select([
                    'lembur.id as lembur_id',
                    'lembur.atasan_id',
                    'lembur.tgl_mulai',
                    'lembur.tgl_selesai',
                    'lembur.jam_mulai',
                    'lembur.jam_selesai',
                    'lembur.status',
                    'lembur.alasan',
                    'lembur.total',
                    'lembur.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('status', '1')
                ->where('lembur.atasan_id', $users->id)
                ->where('lembur.is_deleted', '1')
                ->orderBy('lembur.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('lembur.tgl_mulai', [$start_date, $end_date]);
            }

            $query->where(function ($query) use ($search) {
                $query->where('u1.name', 'LIKE', "%{$search}%")
                    ->orWhere('u2.name', 'LIKE', "%{$search}%")
                    ->orWhere('lembur.alasan', 'LIKE', "%{$search}%");
            });


            if ($request->has('status') && !empty($request->status)) {
                $query->where('lembur.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $diterima = $item->lembur_id ?? '';
                $ditolak = $item->lembur_id ?? '';

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

        return view('karyawan.periksa-kehadiran.lembur.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $lemburs = Lembur::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $lemburs->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogLembur::create([
            'lembur_id' => $id,
            'aktivitas' => 'Menyetujui Lembur karyawan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui lembur karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $lemburs = Lembur::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $lemburs->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogLembur::create([
            'lembur_id' => $id,
            'aktivitas' => 'Menolak Lembur karyawan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak lembur karyawan',
        ]);
    }
}
