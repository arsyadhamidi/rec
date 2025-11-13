<?php

namespace App\Http\Controllers\Operator\Kehadiran;

use App\Exports\KegiatanHarianExport;
use App\Http\Controllers\Controller;
use App\Models\KegiatanHarian;
use App\Models\LogKegiatanHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class OperatorKegiatanHarianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = KegiatanHarian::join('user as u1', 'kegiatan_harian.user_id', '=', 'u1.id')
                ->join('user as u2', 'kegiatan_harian.atasan_id', '=', 'u2.id')
                ->select([
                    'kegiatan_harian.id as kegiatan_id',
                    'kegiatan_harian.atasan_id',
                    'kegiatan_harian.tgl_kegiatan',
                    'kegiatan_harian.keterangan',
                    'kegiatan_harian.status',
                    'kegiatan_harian.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
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

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->kegiatan_id ?? '';

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

        return view('operator.kehadiran.kegiatan-harian.index');
    }

    public function generatepdf(Request $request)
    {
        $query = KegiatanHarian::join('user as u1', 'kegiatan_harian.user_id', '=', 'u1.id')
            ->join('user as u2', 'kegiatan_harian.atasan_id', '=', 'u2.id')
            ->select([
                'kegiatan_harian.id as kegiatan_id',
                'kegiatan_harian.tgl_kegiatan',
                'kegiatan_harian.keterangan',
                'kegiatan_harian.status',
                'kegiatan_harian.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('kegiatan_harian.is_deleted', '1')
            ->orderBy('kegiatan_harian.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('kegiatan_harian.tgl_kegiatan', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('kegiatan_harian.status', $request->status);
        }

        $kegiatans = $query->orderBy('kegiatan_harian.id', 'desc')->get();

        $pdf = PDF::loadview('operator.kehadiran.kegiatan-harian.export-pdf', ['kegiatans' => $kegiatans])->setPaper('A4', 'potrait');
        return $pdf->stream('data-kegiatan-harian.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = KegiatanHarian::join('user as u1', 'kegiatan_harian.user_id', '=', 'u1.id')
            ->join('user as u2', 'kegiatan_harian.atasan_id', '=', 'u2.id')
            ->select([
                'kegiatan_harian.id as kegiatan_id',
                'kegiatan_harian.tgl_kegiatan',
                'kegiatan_harian.keterangan',
                'kegiatan_harian.status',
                'kegiatan_harian.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('kegiatan_harian.is_deleted', '1')
            ->orderBy('kegiatan_harian.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('kegiatan_harian.tgl_kegiatan', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('kegiatan_harian.status', $request->status);
        }

        $data = $query->orderBy('kegiatan_harian.id', 'desc')->get();

        return Excel::download(new KegiatanHarianExport($data), 'data-kegiatan-harian.xlsx');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $kegiatans = KegiatanHarian::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $kegiatans->update([
            'status' => '3',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogKegiatanHarian::create([
            'kegiatan_harian_id' => $id,
            'aktivitas' => 'Menyetujui Kegiatan Harian',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui kegiatan harian karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $kegiatans = KegiatanHarian::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $kegiatans->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogKegiatanHarian::create([
            'kegiatan_harian_id' => $id,
            'aktivitas' => 'Menolak Kegiatan Harian',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak kegiatan harian karyawan',
        ]);
    }
}
