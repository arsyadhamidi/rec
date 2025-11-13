<?php

namespace App\Http\Controllers\Karyawan\Periksa;

use App\Http\Controllers\Controller;
use App\Models\LogPulangCepat;
use App\Models\PulangCepat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanPeriksaPulangCepatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = PulangCepat::join('user as u1', 'pulang_cepat.user_id', '=', 'u1.id')
                ->join('user as u2', 'pulang_cepat.atasan_id', '=', 'u2.id')
                ->select([
                    'pulang_cepat.id as izin_id',
                    'pulang_cepat.user_id',
                    'pulang_cepat.atasan_id',
                    'pulang_cepat.tgl_izin',
                    'pulang_cepat.jam_pulang',
                    'pulang_cepat.jam_selesai',
                    'pulang_cepat.alasan',
                    'pulang_cepat.status',
                    'pulang_cepat.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('pulang_cepat.atasan_id', $users->id)
                ->where('pulang_cepat.status', '1')
                ->where('pulang_cepat.is_deleted', '1')
                ->orderBy('pulang_cepat.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('pulang_cepat.tgl_izin', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('pulang_cepat.status', $request->status);
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

        return view('karyawan.periksa-kehadiran.pulang-cepat.index');
    }

    public function diterima($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = PulangCepat::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'status' => '2',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogPulangCepat::create([
            'pulang_cepat_id' => $id,
            'aktivitas' => 'Menyetujui Izin Pulang Cepat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menyetujui izin pulang cepat karyawan',
        ]);
    }

    public function ditolak($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = PulangCepat::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'status' => '0',
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogPulangCepat::create([
            'pulang_cepat_id' => $id,
            'aktivitas' => 'Menolak Izin Pulang Cepat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menolak izin pulang cepat karyawan',
        ]);
    }
}
