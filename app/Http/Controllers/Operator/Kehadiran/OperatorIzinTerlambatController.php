<?php

namespace App\Http\Controllers\Operator\Kehadiran;

use App\Exports\IzinTerlambatExport;
use App\Http\Controllers\Controller;
use App\Models\IzinTerlambat;
use App\Models\LogIzinTerlambat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class OperatorIzinTerlambatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = IzinTerlambat::join('user as u1', 'izin_terlambat.user_id', '=', 'u1.id')
                ->join('user as u2', 'izin_terlambat.atasan_id', '=', 'u2.id')
                ->select([
                    'izin_terlambat.id as izin_id',
                    'izin_terlambat.tgl_izin',
                    'izin_terlambat.jam_datang',
                    'izin_terlambat.alasan',
                    'izin_terlambat.status',
                    'izin_terlambat.bukti_terlambat',
                    'izin_terlambat.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('izin_terlambat.is_deleted', '1')
                ->orderBy('izin_terlambat.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('izin_terlambat.tgl_izin', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('izin_terlambat.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->izin_id ?? '';

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

        return view('operator.kehadiran.izin-terlambat.index');
    }

    public function generatepdf(Request $request)
    {
        $query = IzinTerlambat::join('user as u1', 'izin_terlambat.user_id', '=', 'u1.id')
            ->join('user as u2', 'izin_terlambat.atasan_id', '=', 'u2.id')
            ->select([
                'izin_terlambat.id as izin_id',
                'izin_terlambat.tgl_izin',
                'izin_terlambat.jam_datang',
                'izin_terlambat.alasan',
                'izin_terlambat.status',
                'izin_terlambat.bukti_terlambat',
                'izin_terlambat.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('izin_terlambat.is_deleted', '1')
            ->orderBy('izin_terlambat.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('izin_terlambat.tgl_izin', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('izin_terlambat.status', $request->status);
        }

        $izins = $query->orderBy('izin_terlambat.id', 'desc')->get();

        $pdf = PDF::loadview('operator.kehadiran.izin-terlambat.export-pdf', ['izins' => $izins])->setPaper('A4', 'potrait');
        return $pdf->stream('data-izin-terlambat.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = IzinTerlambat::join('user as u1', 'izin_terlambat.user_id', '=', 'u1.id')
            ->join('user as u2', 'izin_terlambat.atasan_id', '=', 'u2.id')
            ->select([
                'izin_terlambat.id as izin_id',
                'izin_terlambat.tgl_izin',
                'izin_terlambat.jam_datang',
                'izin_terlambat.alasan',
                'izin_terlambat.status',
                'izin_terlambat.bukti_terlambat',
                'izin_terlambat.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('izin_terlambat.is_deleted', '1')
            ->orderBy('izin_terlambat.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('izin_terlambat.tgl_izin', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('izin_terlambat.status', $request->status);
        }

        $data = $query->orderBy('izin_terlambat.id', 'desc')->get();

        return Excel::download(new IzinTerlambatExport($data), 'data-izin-terlambat.xlsx');
    }

     public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = IzinTerlambat::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'status' => '3',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogIzinTerlambat::create([
            'izin_terlambat_id' => $id,
            'aktivitas' => 'Menyetujui Izin Terlambat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui izin terlambat karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = IzinTerlambat::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogIzinTerlambat::create([
            'izin_terlambat_id' => $id,
            'aktivitas' => 'Menolak Izin Terlambat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak izin terlambat karyawan',
        ]);
    }
}
