<?php

namespace App\Http\Controllers\Karyawan\Master;

use App\Http\Controllers\Controller;
use App\Models\CutiDokter;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\LogCutiDokter;
use App\Models\LogJadwalDokter;
use App\Models\Spesialis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanDokterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Dokter::join('user', 'dokter.user_id', 'user.id')
                ->join('spesialis', 'dokter.spesialis_id', 'spesialis.id')
                ->select([
                    'dokter.id',
                    'dokter.slug',
                    'dokter.nm_dokter',
                    'dokter.tmp_lahir',
                    'dokter.tgl_lahir',
                    'dokter.jk',
                    'dokter.pendidikan',
                    'dokter.alamat',
                    'dokter.telp_dokter',
                    'dokter.tentang',
                    'dokter.keahlian',
                    'dokter.foto_dokter',
                    'dokter.is_deleted',
                    'spesialis.nama_spesialis',
                ])
                ->where('dokter.is_deleted', '1');
            $query->orderBy('dokter.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('dokter.nm_dokter', 'LIKE', "%{$search}%")
                        ->orWhere('dokter.telp_dokter', 'LIKE', "%{$search}%")
                        ->orWhere('spesialis.nama_spesialis', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('spesialis_id') && !empty($request->spesialis_id)) {
                $query->where('dokter.spesialis_id', $request->spesialis_id);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman


            return response()->json([
                'draw' => $request->input('draw'), // Ambil nomor draw dari permintaan
                'recordsTotal' => $totalRecords, // Kirim jumlah total data
                'recordsFiltered' => $totalRecords, // Jumlah data yang difilter sama dengan jumlah total
                'data' => $data->items(), // Kirim data yang sesuai dengan halaman dan jumlah per halaman
            ]);
        }

        $spesialis = Spesialis::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        return view('karyawan.master.dokter.index', [
            'spesialis' => $spesialis,
        ]);
    }

    public function jadwal($id)
    {
        $jadwals = JadwalDokter::join('dokter', 'jadwal_dokter.dokter_id', 'dokter.id')
            ->select([
                'jadwal_dokter.id',
                'jadwal_dokter.dokter_id',
                'jadwal_dokter.hari_dokter',
                'jadwal_dokter.jam_mulai',
                'jadwal_dokter.jam_selesai',
                'jadwal_dokter.is_deleted',
                'dokter.nm_dokter',
            ])
            ->where('jadwal_dokter.is_deleted', '1')
            ->where('jadwal_dokter.dokter_id', $id)
            ->orderBy('jadwal_dokter.id', 'desc')
            ->get();

        $dokters = Dokter::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();
        return view('karyawan.master.dokter.jadwal', [
            'dokters' => $dokters,
            'jadwals' => $jadwals,
        ]);
    }

    public function storejadwaldokter(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required',
            'hari_dokter' => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ], [
            'dokter_id.required'   => 'Pilih nama dokter terlebih dahulu.',
            'hari_dokter.required' => 'Hari praktik dokter wajib diisi.',
            'jam_mulai.required'   => 'Jam mulai praktik wajib diisi.',
            'jam_selesai.required' => 'Jam selesai praktik wajib diisi.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $jadwals = JadwalDokter::create([
            'dokter_id' => $request->dokter_id,
            'hari_dokter' => $request->hari_dokter,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1',
        ]);

        LogJadwalDokter::create([
            'jadwal_dokter_id' => $jadwals->id,
            'aktivitas' => 'Membuat Data Jadwal Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil membuat jadwal dokter');
    }

    public function updatejadwaldokter(Request $request, $id)
    {
        $request->validate([
            'hari_dokter' => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ], [
            'hari_dokter.required' => 'Hari praktik dokter wajib diisi.',
            'jam_mulai.required'   => 'Jam mulai praktik wajib diisi.',
            'jam_selesai.required' => 'Jam selesai praktik wajib diisi.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $jadwals = JadwalDokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $jadwals->update([
            'hari_dokter' => $request->hari_dokter,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogJadwalDokter::create([
            'jadwal_dokter_id' => $jadwals->id,
            'aktivitas' => 'Memperbaharui Data Jadwal Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui jadwal dokter');
    }

    public function destroyjadwaldokter($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();

        $jadwals = JadwalDokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $jadwals->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogJadwalDokter::create([
            'jadwal_dokter_id' => $jadwals->id,
            'aktivitas' => 'Menghapus Data Jadwal Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil menghapus jadwal dokter');
    }

    public function cutidokter($id)
    {
        $cutis = CutiDokter::join('dokter', 'cuti_dokter.dokter_id', 'dokter.id')
            ->select([
                'cuti_dokter.id',
                'cuti_dokter.dokter_id',
                'cuti_dokter.tgl_mulai',
                'cuti_dokter.tgl_selesai',
                'cuti_dokter.is_deleted',
                'dokter.nm_dokter',
            ])
            ->where('cuti_dokter.is_deleted', '1')
            ->where('cuti_dokter.dokter_id', $id)
            ->orderBy('cuti_dokter.id', 'desc')
            ->get();

        $dokters = Dokter::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();
        return view('karyawan.master.dokter.cuti', [
            'dokters' => $dokters,
            'cutis' => $cutis,
        ]);
    }

    public function storecutidokter(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required',
            'tgl_mulai'   => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ], [
            'dokter_id.required'   => 'Silakan pilih dokter yang akan mengambil cuti.',
            'tgl_mulai.required'   => 'Tanggal mulai cuti harus diisi.',
            'tgl_mulai.date'       => 'Format tanggal mulai tidak valid.',
            'tgl_selesai.required' => 'Tanggal selesai cuti harus diisi.',
            'tgl_selesai.date'     => 'Format tanggal selesai tidak valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $cutis = CutiDokter::create([
            'dokter_id' => $request->dokter_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'created_at' => $carbons,
            'created_by' => $users->name ?? 'system',
            'is_deleted' => '1',
        ]);

        LogCutiDokter::create([
            'cuti_dokter_id' => $cutis->id,
            'aktivitas' => 'Membuat Data Cuti Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons->toDateString(),
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil menambahkan data cuti dokter');
    }

    public function updatecutidokter(Request $request, $id)
    {
        $request->validate([
            'tgl_mulai'   => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ], [
            'tgl_mulai.required'   => 'Tanggal mulai cuti harus diisi.',
            'tgl_mulai.date'       => 'Format tanggal mulai tidak valid.',
            'tgl_selesai.required' => 'Tanggal selesai cuti harus diisi.',
            'tgl_selesai.date'     => 'Format tanggal selesai tidak valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $cutis = CutiDokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'updated_at' => $carbons,
            'updated_by' => $users->name ?? 'system',
        ]);

        LogCutiDokter::create([
            'cuti_dokter_id' => $cutis->id,
            'aktivitas' => 'Memperbaharui Data Cuti Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons->toDateString(),
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil memperbaharui data cuti dokter');
    }

    public function destroycutidokter($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();

        $cutis = CutiDokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name ?? 'system',
            'is_deleted' => '0'
        ]);

        LogCutiDokter::create([
            'cuti_dokter_id' => $cutis->id,
            'aktivitas' => 'Menghapus Data Cuti Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons->toDateString(),
        ]);

        return back()->with('success', 'Selamat ! Anda berhasil menghapus data cuti dokter');
    }
}
