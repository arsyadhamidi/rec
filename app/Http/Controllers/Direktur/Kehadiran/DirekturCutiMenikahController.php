<?php

namespace App\Http\Controllers\Direktur\Kehadiran;

use PDF;
use App\Exports\CutiMenikahExport;
use App\Http\Controllers\Controller;
use App\Models\CutiMenikah;
use App\Models\LogCutiMenikah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DirekturCutiMenikahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

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
                $resultid = $item->cuti_id ?? '';

                // Cek status: 2 = bisa diubah, selain itu tidak bisa
                if (($item->status ?? 0) == 3) {
                    $diterimaButton = '
            <button type="button"
                class="btn btn-outline-success btn-diterima"
                data-diterima="' . e($resultid) . '">
            <i class="fas fa-check"></i>
        </button>
        ';

                    $ditolakButton = '
            <button type="button"
                class="btn btn-outline-danger btn-ditolak"
                data-ditolak="' . e($resultid) . '">
            <i class="fas fa-times"></i>
        </button>
        ';
                } else {
                    // Status lain: tampilkan badge, tombol hapus hilang
                    $diterimaButton = '<span class="badge badge-secondary">Tidak Bisa Diperbaharui</span>';
                    $ditolakButton = '';
                }

                // Gabungkan tombol menjadi satu kolom aksi
                $item->aksi = $diterimaButton . $ditolakButton;

                return $item;
            });


            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $dataWithActions,
            ]);
        }

        return view('direktur.kehadiran.cuti-menikah.index');
    }

    public function generatepdf(Request $request)
    {
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
            ->where('cuti_menikah.is_deleted', '1')
            ->orderBy('cuti_menikah.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_menikah.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_menikah.status', $request->status);
        }

        $cutis = $query->orderBy('cuti_menikah.id', 'desc')->get();

        $pdf = PDF::loadview('direktur.kehadiran.cuti-menikah.export-pdf', ['cutis' => $cutis])->setPaper('A4', 'potrait');
        return $pdf->stream('data-cuti-menikah.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = CutiMenikah::join('user as u1', 'cuti_menikah.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_menikah.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'cuti_menikah.pj_id', '=', 'u3.id')
            ->select([
                'cuti_menikah.id as cuti_id',
                'cuti_menikah.user_id',
                'cuti_menikah.atasan_id',
                'cuti_menikah.pj_id',
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
                'u3.name as nama_pj',
            ])
            ->where('cuti_menikah.is_deleted', '1')
            ->orderBy('cuti_menikah.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_menikah.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_menikah.status', $request->status);
        }

        $data = $query->orderBy('cuti_menikah.id', 'desc')->get();

        return Excel::download(new CutiMenikahExport($data), 'data-cuti-menikah.xlsx');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMenikah::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '4',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $id,
            'aktivitas' => 'Menyetujui Cuti Menikah karyawan',
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
        $cutis = CutiMenikah::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $id,
            'aktivitas' => 'Menolak Cuti Menikah karyawan',
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
