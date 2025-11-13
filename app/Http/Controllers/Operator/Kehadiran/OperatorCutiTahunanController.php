<?php

namespace App\Http\Controllers\Operator\Kehadiran;

use App\Exports\CutiTahunanExport;
use App\Http\Controllers\Controller;
use App\Models\CutiTahunan;
use App\Models\LogCutiTahunan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class OperatorCutiTahunanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = CutiTahunan::join('user as u1', 'cuti_tahunan.user_id', '=', 'u1.id')
                ->join('user as u2', 'cuti_tahunan.atasan_id', '=', 'u2.id')
                ->join('user as u3', 'cuti_tahunan.pj_id', '=', 'u3.id')
                ->select([
                    'cuti_tahunan.id as cuti_id',
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
                $resultid = $item->cuti_id ?? '';

                // Cek status: 2 = bisa diubah, selain itu tidak bisa
                if (($item->status ?? 0) == 2) {
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

        return view('operator.kehadiran.cuti-tahunan.index');
    }

    public function generatepdf(Request $request)
    {
        $query = CutiTahunan::join('user as u1', 'cuti_tahunan.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_tahunan.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'cuti_tahunan.pj_id', '=', 'u3.id')
            ->select([
                'cuti_tahunan.id as cuti_id',
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
            ->where('cuti_tahunan.is_deleted', '1')
            ->orderBy('cuti_tahunan.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_tahunan.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_tahunan.status', $request->status);
        }

        $cutis = $query->orderBy('cuti_tahunan.id', 'desc')->get();

        $pdf = PDF::loadview('operator.kehadiran.cuti-tahunan.export-pdf', ['cutis' => $cutis])->setPaper('A4', 'potrait');
        return $pdf->stream('data-cuti-tahunan.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = CutiTahunan::join('user as u1', 'cuti_tahunan.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_tahunan.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'cuti_tahunan.pj_id', '=', 'u3.id')
            ->select([
                'cuti_tahunan.id as cuti_id',
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
            ->where('cuti_tahunan.is_deleted', '1')
            ->orderBy('cuti_tahunan.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_tahunan.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_tahunan.status', $request->status);
        }

        $data = $query->orderBy('cuti_tahunan.id', 'desc')->get();

        return Excel::download(new CutiTahunanExport($data), 'data-cuti-tahunan.xlsx');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiTahunan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '3',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiTahunan::create([
            'cuti_tahunan_id' => $id,
            'aktivitas' => 'Menyetujui cuti tahunan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui cuti tahunan karyawan',
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
