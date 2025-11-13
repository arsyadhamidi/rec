<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\LogIzinKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaIzinKeluarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = IzinKeluar::join('user as u1', 'izin_keluar.user_id', '=', 'u1.id')
                ->join('user as u2', 'izin_keluar.atasan_id', '=', 'u2.id')
                ->select([
                    'izin_keluar.id as izin_id',
                    'izin_keluar.user_id',
                    'izin_keluar.atasan_id',
                    'izin_keluar.tgl_izin',
                    'izin_keluar.jam_keluar',
                    'izin_keluar.jam_kembali',
                    'izin_keluar.keperluan',
                    'izin_keluar.status',
                    'izin_keluar.tahun',
                    'izin_keluar.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('izin_keluar.status', '1')
                ->where('izin_keluar.atasan_id', $users->id)
                ->where('izin_keluar.is_deleted', '1')
                ->orderBy('izin_keluar.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('izin_keluar.tgl_izin', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('izin_keluar.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $diterima = $item->izin_id ?? '';
                $ditolak = $item->izin_id ?? '';

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

        return view('karyawan.periksa-kehadiran.izin-keluar.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = IzinKeluar::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogIzinKeluar::create([
            'izin_keluar_id' => $id,
            'aktivitas' => 'Menyetujui Izin Keluar',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui izin keluar',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = IzinKeluar::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogIzinKeluar::create([
            'izin_keluar_id' => $id,
            'aktivitas' => 'Menolak Izin Keluar',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak izin keluar dari jam dinas karyawan',
        ]);
    }
}
