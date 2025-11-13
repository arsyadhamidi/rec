<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Exports\CutiTahunanExport;
use App\Http\Controllers\Controller;
use App\Models\CutiTahunan;
use App\Models\KuotaCuti;
use App\Models\KuotaCutiTahunan;
use App\Models\LogCuti;
use App\Models\LogCutiTahunan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminCutiTahunanController extends Controller
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
                $editUrl = route("admin-cutitahunan.edit", $item->cuti_id ?? '');

                $item->aksi = '
        <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button"
                class="btn btn-outline-danger btn-delete"
                data-resultid="' . e($resultid) . '">
            <i class="fas fa-trash-alt"></i>
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

        return view('admin.kehadiran.cuti-tahunan.index');
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

        $pdf = PDF::loadview('admin.kehadiran.cuti-tahunan.export-pdf', ['cutis' => $cutis])->setPaper('A4', 'potrait');
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

    public function create()
    {
        return view('admin.kehadiran.cuti-tahunan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'pj_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tgl_masuk' => 'required|date|after_or_equal:tgl_selesai',
            'status' => 'required',
            'ket_status' => 'nullable|max:500',
        ], [
            // Pesan error umum
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after_or_equal' => 'Tanggal :attribute harus sama atau setelah :other.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
                'file' => 'Ukuran file :attribute maksimal :max kilobyte.',
            ],
            'mimes' => 'File :attribute harus berupa file dengan format: jpg, jpeg, png, atau pdf.',

            // Pesan error khusus setiap field
            'user_id.required' => 'Karyawan harus dipilih.',
            'atasan_id.required' => 'Atasan langsung harus dipilih.',
            'pj_id.required' => 'Penanggung jawab harus dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai cuti wajib diisi.',
            'tgl_selesai.required' => 'Tanggal selesai cuti wajib diisi.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'tgl_masuk.required' => 'Tanggal masuk kerja kembali wajib diisi.',
            'status.required' => 'Status pengajuan wajib diisi.',
            'ket_status.max' => 'Keterangan status maksimal 500 karakter.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        // Cek Kuota Cuti
        $kuotaCutis = KuotaCutiTahunan::where('user_id', $request->user_id)
            ->where('tahun', $carbons->format('Y'))
            ->orderBy('id', 'desc')
            ->first();

        // Hitung jumlah hari cuti
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahCuti = $tglSelesai->diffInDays($tglMulai) + 1;
        $sisaKuotaCutis = $kuotaCutis->sisa_kuota ?? '12';

        if ($sisaKuotaCutis < $jumlahCuti) {
            return back()->with('error', 'Maaf ! Sisa Kuota Cuti Anda tidak mencukupi silahkan melapor ke pihak SDM');
        }

        // $tinggalKuotaCuti = $sisaKuotaCutis - $jumlahCuti;
        // dd($tinggalKuotaCuti);
        if ($request->status == '4') {
            KuotaCutiTahunan::create([
                'user_id' => $request->user_id,
                'tahun' => $carbons->format('Y'),
                'kuota_awal' => '12',
                'kuota_terpakai' => $jumlahCuti,
            ]);
        }

        $cutis = CutiTahunan::create([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'pj_id' => $request->pj_id,
            'tgl_pengajuan' => $carbons,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $jumlahCuti ?? '0',
            'status' => $request->status,
            'ket_status' => $request->ket_status,
            'tahun' => $carbons->format('Y'),
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogCutiTahunan::create([
            'cuti_tahunan_id' => $cutis->id,
            'aktivitas' => 'Membuat Data Cuti',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-cutitahunan.index')->with('success', 'Selamat ! Anda berhasil membuat Data Pengajuan Cuti');
    }

    public function edit($id)
    {
        $cutis = CutiTahunan::join('user as u1', 'cuti_tahunan.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_tahunan.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'cuti_tahunan.pj_id', '=', 'u3.id')
            ->select([
                'cuti_tahunan.id as cuti_id',
                'cuti_tahunan.user_id',
                'cuti_tahunan.atasan_id',
                'cuti_tahunan.pj_id',
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
            ->where('cuti_tahunan.id', $id)
            ->where('cuti_tahunan.is_deleted', '1')
            ->orderBy('cuti_tahunan.id', 'desc')
            ->first();
        return view('admin.kehadiran.cuti-tahunan.edit', [
            'cutis' => $cutis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'pj_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tgl_masuk' => 'required|date|after_or_equal:tgl_selesai',
            'status' => 'required',
            'ket_status' => 'nullable|max:500',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after_or_equal' => 'Tanggal :attribute harus sama atau setelah :other.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
                'file' => 'Ukuran file :attribute maksimal :max kilobyte.',
            ],
            'user_id.required' => 'Karyawan harus dipilih.',
            'atasan_id.required' => 'Atasan langsung harus dipilih.',
            'pj_id.required' => 'Penanggung jawab harus dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai cuti wajib diisi.',
            'tgl_selesai.required' => 'Tanggal selesai cuti wajib diisi.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'tgl_masuk.required' => 'Tanggal masuk kerja kembali wajib diisi.',
            'status.required' => 'Status pengajuan wajib diisi.',
            'ket_status.max' => 'Keterangan status maksimal 500 karakter.',
        ]);

        $users = Auth::user();
        $now = Carbon::now();

        $cuti = CutiTahunan::where('id', $id)->where('is_deleted', '1')->firstOrFail();

        // Hitung lama cuti baru
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $lamaCutiBaru = $tglSelesai->diffInDays($tglMulai) + 1;

        // Ambil kuota cuti tahun ini
        $kuota = KuotaCutiTahunan::firstOrCreate(
            ['user_id' => $request->user_id, 'tahun' => $now->year],
            ['kuota_awal' => 12, 'kuota_terpakai' => 0]
        );

        $sisaKuota = $kuota->kuota_awal - $kuota->kuota_terpakai;

        // Logika jika status berubah
        $statusLama = $cuti->status;
        $lamaCutiLama = $cuti->lama_cuti;

        /**
         * ==========================
         * LOGIKA KUOTA CUTI
         * ==========================
         */

        // Jika status berubah menjadi "disetujui"
        if ($statusLama != 4 && $request->status == 4) {
            if ($sisaKuota < $lamaCutiBaru) {
                return back()->with('error', 'Sisa kuota cuti tidak mencukupi.');
            }
            $kuota->increment('kuota_terpakai', $lamaCutiBaru);
        }

        // Jika status berubah dari "disetujui" ke selain 4 (dibatalkan/diproses)
        if ($statusLama == 4 && $request->status != 4) {
            $kuota->decrement('kuota_terpakai', $lamaCutiLama);
        }

        // Jika status tetap "disetujui" tapi tanggal cuti berubah
        if ($statusLama == 4 && $request->status == 4) {
            if ($lamaCutiLama != $lamaCutiBaru) {
                // Kembalikan kuota lama, lalu potong dengan yang baru
                $kuota->decrement('kuota_terpakai', $lamaCutiLama);

                $sisaKuota = $kuota->kuota_awal - $kuota->kuota_terpakai;
                if ($sisaKuota < $lamaCutiBaru) {
                    return back()->with('error', 'Sisa kuota cuti tidak mencukupi untuk perubahan tanggal.');
                }

                $kuota->increment('kuota_terpakai', $lamaCutiBaru);
            }
        }

        /**
         * ==========================
         * UPDATE DATA CUTI
         * ==========================
         */
        $cuti->update([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'pj_id' => $request->pj_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $lamaCutiBaru,
            'status' => $request->status,
            'ket_status' => $request->ket_status,
            'tahun' => $now->year,
            'updated_at' => $now,
            'updated_by' => $users->name,
        ]);

        LogCutiTahunan::create([
            'cuti_tahunan_id' => $cuti->id,
            'aktivitas' => 'Memperbaharui Data Cuti',
            'user' => $users->name,
            'tanggal' => $now->toDateString(),
            'waktu_dibuat' => $now,
        ]);

        return redirect()->route('admin-cutitahunan.index')
            ->with('success', 'Data pengajuan cuti berhasil diperbaharui.');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $now = Carbon::now();

        // Ambil data cuti
        $cuti = CutiTahunan::where('id', $id)
            ->where('is_deleted', '1')
            ->firstOrFail();

        // Ambil kuota cuti tahunan user
        $kuota = KuotaCutiTahunan::where('user_id', $cuti->user_id)
            ->where('tahun', $cuti->tahun)
            ->first();

        // Jika sebelumnya disetujui, maka kembalikan kuota cuti
        $kuota->decrement('kuota_terpakai', $cuti->lama_cuti);

        // Update is_deleted menjadi 0 (soft delete)
        $cuti->update([
            'is_deleted' => '0',
            'deleted_at' => $now,
            'deleted_by' => $users->name,
        ]);

        // Simpan log aktivitas
        LogCutiTahunan::create([
            'cuti_tahunan_id' => $cuti->id,
            'aktivitas' => 'Menghapus Data Cuti (is_deleted = 0)',
            'user' => $users->name,
            'tanggal' => $now->toDateString(),
            'waktu_dibuat' => $now,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Data pengajuan cuti berhasil dihapus dan kuota cuti telah dikembalikan.',
        ]);
    }


    public function getUser(Request $request)
    {
        $id = $request->get('id');
        $searchTerm = $request->get('q');

        $query = User::select([
            'user.id',
            'user.name',
            'user.is_deleted',
        ]);

        if ($id) {
            $item = $query->where('id', $id)->first();
            return response()->json(['item' => $item]);
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('user.id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('user.name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $results = $query->where('is_deleted', '1')->get();
        return response()->json(['items' => $results]);
    }
}
