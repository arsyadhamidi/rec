<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\KegiatanHarian;
use App\Models\LogKegiatanHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaKegiatanHarianController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
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
                ->where('status', '1')
                ->where('kegiatan_harian.atasan_id', $users->id)
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
             // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $diterima = $item->kegiatan_id ?? '';
                $ditolak = $item->kegiatan_id ?? '';

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

        return view('karyawan.periksa-kehadiran.kegiatan-harian.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $kegiatans = KegiatanHarian::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $kegiatans->update([
            'status' => '2',
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
