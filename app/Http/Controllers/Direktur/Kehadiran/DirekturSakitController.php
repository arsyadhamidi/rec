<?php

namespace App\Http\Controllers\Direktur\Kehadiran;

use PDF;
use App\Exports\SakitExport;
use App\Http\Controllers\Controller;
use App\Models\LogSakit;
use App\Models\Sakit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DirekturSakitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Sakit::join('user as u1', 'sakit.user_id', '=', 'u1.id')
                ->join('user as u2', 'sakit.atasan_id', '=', 'u2.id')
                ->select([
                    'sakit.id as sakit_id',
                    'sakit.tgl_mulai',
                    'sakit.tgl_selesai',
                    'sakit.alasan',
                    'sakit.status',
                    'sakit.bukti_sakit',
                    'sakit.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('sakit.is_deleted', '1')
                ->orderBy('sakit.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('sakit.tgl_mulai', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('sakit.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->sakit_id ?? '';

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

        return view('direktur.kehadiran.sakit.index');
    }

    public function generatepdf(Request $request)
    {
        $query = Sakit::join('user as u1', 'sakit.user_id', '=', 'u1.id')
            ->join('user as u2', 'sakit.atasan_id', '=', 'u2.id')
            ->select([
                'sakit.id as sakit_id',
                'sakit.tgl_mulai',
                'sakit.tgl_selesai',
                'sakit.alasan',
                'sakit.status',
                'sakit.bukti_sakit',
                'sakit.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('sakit.is_deleted', '1')
            ->orderBy('sakit.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('sakit.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('sakit.status', $request->status);
        }

        $sakits = $query->orderBy('sakit.id', 'desc')->get();

        $pdf = PDF::loadview('direktur.kehadiran.sakit.export-pdf', ['sakits' => $sakits])->setPaper('A4', 'potrait');
        return $pdf->stream('data-sakit.pdf');
    }

    public function generateexcel(Request $request)
    {
       $query = Sakit::join('user as u1', 'sakit.user_id', '=', 'u1.id')
            ->join('user as u2', 'sakit.atasan_id', '=', 'u2.id')
            ->select([
                'sakit.id as sakit_id',
                'sakit.tgl_mulai',
                'sakit.tgl_selesai',
                'sakit.alasan',
                'sakit.status',
                'sakit.bukti_sakit',
                'sakit.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('sakit.is_deleted', '1')
            ->orderBy('sakit.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('sakit.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('sakit.status', $request->status);
        }

        $data = $query->orderBy('sakit.id', 'desc')->get();

        return Excel::download(new SakitExport($data), 'data-sakit.xlsx');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $sakits = Sakit::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $sakits->update([
            'status' => '4',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogSakit::create([
            'sakit_id' => $id,
            'aktivitas' => 'Menyetujui Sakit karyawan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui izin sakit karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $sakits = Sakit::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $sakits->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogSakit::create([
            'sakit_id' => $id,
            'aktivitas' => 'Menolak Sakit karyawan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak izin sakit karyawan',
        ]);
    }
}
