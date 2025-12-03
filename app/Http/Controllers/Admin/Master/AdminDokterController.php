<?php

namespace App\Http\Controllers\Admin\Master;

use App\Exports\DokterExport;
use App\Http\Controllers\Controller;
use App\Models\CutiDokter;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\LogCutiDokter;
use App\Models\LogDokter;
use App\Models\LogJadwalDokter;
use App\Models\Spesialis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminDokterController extends Controller
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
        return view('admin.master.dokter.index', [
            'spesialis' => $spesialis,
        ]);
    }

    public function generatepdf(Request $request)
    {
        $query = Dokter::leftJoin('spesialis', 'dokter.spesialis_id', 'spesialis.id')
            ->select([
                'dokter.id',
                'dokter.spesialis_id',
                'dokter.slug',
                'dokter.nm_dokter',
                'dokter.tmp_lahir',
                'dokter.tgl_lahir',
                'dokter.jk',
                'dokter.alamat',
                'dokter.telp_dokter',
                'dokter.tentang',
                'dokter.pendidikan',
                'dokter.keahlian',
                'spesialis.nama_spesialis',
            ])
            ->where('dokter.is_deleted', '1');

        if ($request->filled('spesialis_id')) {
            $query->where('dokter.spesialis_id', $request->spesialis_id);
        }

        $dokters = $query->orderBy('dokter.id', 'desc')->get();

        $pdf = PDF::loadview('admin.master.dokter.export-pdf', ['dokters' => $dokters])->setPaper('A4', 'potrait');
        return $pdf->stream('data-dokter.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = Dokter::leftJoin('spesialis', 'dokter.spesialis_id', 'spesialis.id')
            ->select([
                'dokter.id',
                'dokter.spesialis_id',
                'dokter.slug',
                'dokter.nm_dokter',
                'dokter.tmp_lahir',
                'dokter.tgl_lahir',
                'dokter.jk',
                'dokter.alamat',
                'dokter.telp_dokter',
                'dokter.tentang',
                'dokter.pendidikan',
                'dokter.keahlian',
                'spesialis.nama_spesialis',
            ])
            ->where('dokter.is_deleted', '1');

        if ($request->filled('spesialis_id')) {
            $query->where('dokter.spesialis_id', $request->spesialis_id);
        }

        $data = $query->orderBy('dokter.id', 'desc')->get();
        return Excel::download(new DokterExport($data), 'data-dokter.xlsx');
    }

    public function create()
    {
        $spesialis = Spesialis::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        $users = User::where('level_id', '2')->where('is_deleted', '1')->orderBy('id', 'desc')->get();

        return view('admin.master.dokter.create', [
            'spesialis' => $spesialis,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required',
            'spesialis_id'   => 'required',
            'slug'           => 'required|max:255|unique:dokter,slug',
            'nm_dokter'      => 'required|max:200',
            'tmp_lahir'      => 'required|max:100',
            'tgl_lahir'      => 'required|date',
            'jk'             => 'required',
            'pendidikan'     => 'required|max:500',
            'alamat'         => 'required|max:500',
            'telp_dokter'    => 'required|max:20',
            'tentang'        => 'required|max:1000',
            'pengalaman'     => 'required|max:1000',
            'organisasi'     => 'required|max:1000',
            'fellowship'     => 'required|max:1000',
            'keahlian'       => 'required|max:1000',
            'foto_dokter'    => 'nullable|mimes:png,jpg,jpeg|max:10248',
        ], [
            'user_id.required'        => 'User wajib dipilih.',
            'spesialis_id.required'   => 'Spesialis wajib dipilih.',
            'slug.required'           => 'Slug wajib diisi.',
            'slug.max'                => 'Slug maksimal 255 karakter.',
            'slug.unique'             => 'Slug sudah digunakan, silakan gunakan yang lain.',

            'nm_dokter.required'      => 'Nama dokter wajib diisi.',
            'nm_dokter.max'           => 'Nama dokter maksimal 200 karakter.',

            'tmp_lahir.required'      => 'Tempat lahir wajib diisi.',
            'tmp_lahir.max'           => 'Tempat lahir maksimal 100 karakter.',

            'tgl_lahir.required'      => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date'          => 'Tanggal lahir harus berupa format tanggal yang valid.',

            'jk.required'             => 'Jenis kelamin wajib dipilih.',

            'pendidikan.required'     => 'Riwayat pendidikan wajib diisi.',
            'pendidikan.max'          => 'Riwayat pendidikan maksimal 500 karakter.',

            'alamat.required'         => 'Alamat wajib diisi.',
            'alamat.max'              => 'Alamat maksimal 500 karakter.',

            'telp_dokter.required'    => 'Nomor telepon wajib diisi.',
            'telp_dokter.max'         => 'Nomor telepon maksimal 20 karakter.',

            'tentang.required'        => 'Bagian tentang dokter wajib diisi.',
            'tentang.max'             => 'Tentang dokter maksimal 1000 karakter.',

            'pengalaman.required'     => 'Pengalaman wajib diisi.',
            'pengalaman.max'          => 'Pengalaman maksimal 1000 karakter.',

            'organisasi.required'     => 'Organisasi wajib diisi.',
            'organisasi.max'          => 'Organisasi maksimal 1000 karakter.',

            'fellowship.required'     => 'Fellowship wajib diisi.',
            'fellowship.max'          => 'Fellowship maksimal 1000 karakter.',

            'keahlian.required'       => 'Keahlian wajib diisi.',
            'keahlian.max'            => 'Keahlian maksimal 1000 karakter.',

            'foto_dokter.mimes'       => 'Foto harus berupa file PNG, JPG, atau JPEG.',
            'foto_dokter.max'         => 'Ukuran foto maksimal 10MB.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $fotoDokter = null;
        if ($request->file('foto_dokter')) {
            $fotoDokter = $request->file('foto_dokter')->store('foto_dokter');
        }

        $dokters = Dokter::create([
            'user_id' => $request->user_id,
            'spesialis_id' => $request->spesialis_id,
            'slug' => $request->slug,
            'nm_dokter' => $request->nm_dokter,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,

            'jk' => $request->jk,
            'pendidikan' => $request->pendidikan,
            'alamat' => $request->alamat,
            'telp_dokter' => $request->telp_dokter,
            'tentang' => $request->tentang,
            'pengalaman' => $request->pengalaman,
            'fellowship' => $request->fellowship,
            'organisasi' => $request->organisasi,
            'keahlian' => $request->keahlian,
            'foto_dokter' => $fotoDokter,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1',
        ]);

        LogDokter::create([
            'dokter_id' => $dokters->id,
            'aktivitas' => 'Membuat Data Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-dokter.index')->with('success', 'Selamat ! Anda berhasil menambahkan data dokter');
    }

    public function edit($id)
    {
        $spesialis = Spesialis::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        $users = User::where('level_id', '2')->where('is_deleted', '1')->orderBy('id', 'desc')->get();

        $dokters = Dokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        return view('admin.master.dokter.edit', [
            'spesialis' => $spesialis,
            'users' => $users,
            'dokters' => $dokters,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'       => 'required',
            'spesialis_id'  => 'required',
            'slug'          => 'required|max:255|unique:dokter,slug',
            'nm_dokter'     => 'required|max:200',
            'tmp_lahir'     => 'required|max:100',
            'tgl_lahir'     => 'required|date',
            'jk'            => 'required',
            'alamat'        => 'required|max:500',
            'telp_dokter'   => 'required|max:20',
            'keahlian'      => 'required|max:1000',
            'pendidikan'    => 'required|max:500',
            'fellowship'    => 'required|max:1000',
            'pengalaman'    => 'required|max:1000',
            'organisasi'    => 'required|max:1000',
            'tentang'       => 'required|max:1000',
            'foto_dokter'   => 'nullable|mimes:png,jpg,jpeg|max:10248',
        ], [
            'user_id.required'        => 'User wajib dipilih.',
            'spesialis_id.required'   => 'Spesialis wajib dipilih.',

            'slug.required'           => 'Slug tidak boleh kosong.',
            'slug.max'                => 'Slug tidak boleh lebih dari 255 karakter.',
            'slug.unique'             => 'Slug sudah digunakan, silakan gunakan slug lain.',

            'nm_dokter.required'      => 'Nama dokter tidak boleh kosong.',
            'nm_dokter.max'           => 'Nama dokter maksimal 200 karakter.',

            'tmp_lahir.required'      => 'Tempat lahir tidak boleh kosong.',
            'tmp_lahir.max'           => 'Tempat lahir maksimal 100 karakter.',

            'tgl_lahir.required'      => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date'          => 'Format tanggal lahir tidak valid.',

            'jk.required'             => 'Jenis kelamin wajib dipilih.',

            'alamat.required'         => 'Alamat tidak boleh kosong.',
            'alamat.max'              => 'Alamat maksimal 500 karakter.',

            'telp_dokter.required'    => 'Nomor telepon wajib diisi.',
            'telp_dokter.max'         => 'Nomor telepon maksimal 20 digit.',

            'keahlian.required'       => 'Keahlian tidak boleh kosong.',
            'keahlian.max'            => 'Keahlian maksimal 1000 karakter.',

            'pendidikan.required'     => 'Pendidikan tidak boleh kosong.',
            'pendidikan.max'          => 'Pendidikan maksimal 500 karakter.',

            'fellowship.required'     => 'Fellowship tidak boleh kosong.',
            'fellowship.max'          => 'Fellowship maksimal 1000 karakter.',

            'pengalaman.required'     => 'Pengalaman tidak boleh kosong.',
            'pengalaman.max'          => 'Pengalaman maksimal 1000 karakter.',

            'organisasi.required'     => 'Organisasi tidak boleh kosong.',
            'organisasi.max'          => 'Organisasi maksimal 1000 karakter.',

            'tentang.required'        => 'Tentang dokter tidak boleh kosong.',
            'tentang.max'             => 'Tentang dokter maksimal 1000 karakter.',

            'foto_dokter.mimes'       => 'Foto dokter harus berupa file PNG, JPG, atau JPEG.',
            'foto_dokter.max'         => 'Ukuran foto dokter maksimal 10MB.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $dokters = Dokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $fotoDokter = null;
        if ($request->file('foto_dokter')) {
            if ($dokters->foto_dokter) {
                Storage::delete($dokters->foto_dokter);
            }
            $fotoDokter = $request->file('foto_dokter')->store('foto_dokter');
        } else {
            $fotoDokter = $dokters->foto_dokter;
        }

        $dokters->update([
            'user_id' => $request->user_id,
            'spesialis_id' => $request->spesialis_id,
            'slug' => $request->slug,
            'nm_dokter' => $request->nm_dokter,
            'tmp_lahir' => $request->tmp_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jk' => $request->jk,
            'pendidikan' => $request->pendidikan,
            'organisasi' => $request->organisasi,
            'pengalaman' => $request->pengalaman,
            'fellowship' => $request->fellowship,
            'alamat' => $request->alamat,
            'telp_dokter' => $request->telp_dokter,
            'tentang' => $request->tentang,
            'keahlian' => $request->keahlian,
            'foto_dokter' => $fotoDokter,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogDokter::create([
            'dokter_id' => $dokters->id,
            'aktivitas' => 'Memperbaharui Data Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-dokter.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data dokter');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();

        $dokters = Dokter::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $fotoDokter = null;
        if ($dokters->foto_dokter) {
            Storage::delete($dokters->foto_dokter);
        }

        $dokters->update([
            'foto_dokter' => $fotoDokter,
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogDokter::create([
            'dokter_id' => $dokters->id,
            'aktivitas' => 'Menghapus Data Dokter',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data dokter',
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
        return view('admin.master.dokter.jadwal', [
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
                'cuti_dokter.status',
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
        return view('admin.master.dokter.cuti', [
            'dokters' => $dokters,
            'cutis' => $cutis,
        ]);
    }

    public function storecutidokter(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required',
            'status'   => 'required',
            'tgl_mulai'   => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ], [
            'dokter_id.required'   => 'Silakan pilih dokter yang akan mengambil cuti.',
            'status.required'   => 'Silahkan pilih Status dokter yang akan mengambil cuti.',
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
            'status' => $request->status,
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
            'status'   => 'required',
        ], [
            'status.required'   => 'Silahkan pilih Status dokter yang akan mengambil cuti.',
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
            'status' => $request->status,
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
