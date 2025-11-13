<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Exports\CutiMenikahExport;
use App\Http\Controllers\Controller;
use App\Models\CutiMenikah;
use App\Models\LogCutiDokter;
use App\Models\LogCutiMenikah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminCutiMenikahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = CutiMenikah::join('user as u1', 'cuti_menikah.user_id', '=', 'u1.id')
                ->join('user as u2', 'cuti_menikah.atasan_id', '=', 'u2.id')
                ->select([
                    'cuti_menikah.id as cuti_id',
                    'cuti_menikah.user_id',
                    'cuti_menikah.atasan_id',
                    'cuti_menikah.tgl_mulai',
                    'cuti_menikah.tgl_selesai',
                    'cuti_menikah.tgl_masuk',
                    'cuti_menikah.lama_cuti',
                    'cuti_menikah.alasan',
                    'cuti_menikah.status',
                    'cuti_menikah.bukti_menikah',
                    'cuti_menikah.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('cuti_menikah.is_deleted', '1')
                ->orderBy('cuti_menikah.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('cuti_menikah.tgl_mulai', [$start_date, $end_date]);
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('cuti_menikah.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->cuti_id ?? '';
                $editUrl = route("admin-cutimenikah.edit", $item->cuti_id ?? '');
                $imageUrl = asset('storage/' . $item->bukti_menikah);

                $item->aksi = '
        <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
            <i class="fas fa-edit"></i>
        </a>
        <a href="' . $imageUrl . '" class="btn btn-outline-warning mx-1" target="_blank">
            <i class="fas fa-image"></i>
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

        return view('admin.kehadiran.cuti-menikah.index');
    }

    public function generatepdf(Request $request)
    {
        $query = CutiMenikah::join('user as u1', 'cuti_menikah.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_menikah.atasan_id', '=', 'u2.id')
            ->select([
                'cuti_menikah.id as cuti_id',
                'cuti_menikah.user_id',
                'cuti_menikah.atasan_id',
                'cuti_menikah.tgl_mulai',
                'cuti_menikah.tgl_selesai',
                'cuti_menikah.tgl_masuk',
                'cuti_menikah.lama_cuti',
                'cuti_menikah.alasan',
                'cuti_menikah.status',
                'cuti_menikah.bukti_menikah',
                'cuti_menikah.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('cuti_menikah.is_deleted', '1')
            ->orderBy('cuti_menikah.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_menikah.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_menikah.status', $request->status);
        }

        $cutis = $query->orderBy('cuti_menikah.id', 'desc')->get();

        $pdf = PDF::loadview('admin.kehadiran.cuti-menikah.export-pdf', ['cutis' => $cutis])->setPaper('A4', 'potrait');
        return $pdf->stream('data-cuti-menikah.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = CutiMenikah::join('user as u1', 'cuti_menikah.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_menikah.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'cuti_menikah.pj_id', '=', 'u3.id')
            ->select([
                'cuti_menikah.id as cuti_id',
                'cuti_menikah.user_id',
                'cuti_menikah.atasan_id',
                'cuti_menikah.pj_id',
                'cuti_menikah.tgl_mulai',
                'cuti_menikah.tgl_selesai',
                'cuti_menikah.tgl_masuk',
                'cuti_menikah.lama_cuti',
                'cuti_menikah.alasan',
                'cuti_menikah.status',
                'cuti_menikah.bukti_menikah',
                'cuti_menikah.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
                'u3.name as nama_pj',
            ])
            ->where('cuti_menikah.is_deleted', '1')
            ->orderBy('cuti_menikah.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_menikah.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_menikah.status', $request->status);
        }

        $data = $query->orderBy('cuti_menikah.id', 'desc')->get();

        return Excel::download(new CutiMenikahExport($data), 'data-cuti-menikah.xlsx');
    }

    public function create()
    {
        return view('admin.kehadiran.cuti-menikah.create');
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
            'alasan' => 'required|max:500',
            'status' => 'required',
            'bukti_menikah' => 'required|mimes:jpg,png,jpeg|max:10248',
        ], [
            'user_id.required' => 'Pegawai wajib dipilih.',
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',
            'pj_id.required' => 'Penanggung Jawab langsung wajib dipilih.',

            'tgl_mulai.required' => 'Tanggal mulai cuti wajib diisi.',
            'tgl_mulai.date' => 'Tanggal mulai cuti tidak valid.',

            'tgl_selesai.required' => 'Tanggal selesai cuti wajib diisi.',
            'tgl_selesai.date' => 'Tanggal selesai cuti tidak valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',

            'tgl_masuk.required' => 'Tanggal masuk kerja kembali wajib diisi.',
            'tgl_masuk.date' => 'Tanggal masuk kerja kembali tidak valid.',
            'tgl_masuk.after_or_equal' => 'Tanggal masuk kerja kembali tidak boleh sebelum tanggal selesai cuti.',

            'alasan.required' => 'Alasan cuti wajib diisi.',
            'alasan.max' => 'Alasan cuti maksimal 500 karakter.',

            'status.required' => 'Status pengajuan cuti wajib diisi.',

            'bukti_menikah.required' => 'Bukti cuti melahirkan wajib diunggah.',
            'bukti_menikah.mimes' => 'Bukti cuti harus berupa file gambar dengan format JPG, PNG, atau JPEG.',
            'bukti_menikah.max' => 'Ukuran file bukti maksimal 10MB.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $buktiMenikah = null;
        if ($request->file('bukti_menikah')) {
            $buktiMenikah = $request->file('bukti_menikah')->store('bukti_menikah');
        }

        // Hitung jumlah hari cuti
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahCuti = $tglSelesai->diffInDays($tglMulai) + 1;

        $cutis = CutiMenikah::create([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'pj_id' => $request->pj_id,
            'tgl_pengajuan' => $carbons,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $jumlahCuti,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'bukti_menikah' => $buktiMenikah,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $cutis->id,
            'aktivitas' => 'Membuat Data Cuti Menikah',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-cutimenikah.index')->with('success', 'Selamat ! Anda berhasil menambahkan data cuti menikah!');
    }

    public function edit($id)
    {
        $cutis = CutiMenikah::join('user as u1', 'cuti_menikah.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_menikah.atasan_id', '=', 'u2.id')
            ->join('user as u3', 'cuti_menikah.atasan_id', '=', 'u3.id')
            ->select([
                'cuti_menikah.id as cuti_id',
                'cuti_menikah.user_id',
                'cuti_menikah.atasan_id',
                'cuti_menikah.pj_id',
                'cuti_menikah.tgl_mulai',
                'cuti_menikah.tgl_selesai',
                'cuti_menikah.tgl_masuk',
                'cuti_menikah.lama_cuti',
                'cuti_menikah.alasan',
                'cuti_menikah.status',
                'cuti_menikah.bukti_menikah',
                'cuti_menikah.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
                'u3.name as nama_pj',
            ])
            ->where('cuti_menikah.id', $id)
            ->where('cuti_menikah.is_deleted', '1')
            ->orderBy('cuti_menikah.id', 'desc')
            ->first();
        return view('admin.kehadiran.cuti-menikah.edit', [
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
            'alasan' => 'required|max:500',
            'status' => 'required',
            'bukti_menikah' => 'mimes:jpg,png,jpeg|max:10248',
        ], [
            'user_id.required' => 'Pegawai wajib dipilih.',
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',
            'pj_id.required' => 'Penanggung Jawab langsung wajib dipilih.',

            'tgl_mulai.required' => 'Tanggal mulai cuti wajib diisi.',
            'tgl_mulai.date' => 'Tanggal mulai cuti tidak valid.',

            'tgl_selesai.required' => 'Tanggal selesai cuti wajib diisi.',
            'tgl_selesai.date' => 'Tanggal selesai cuti tidak valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',

            'tgl_masuk.required' => 'Tanggal masuk kerja kembali wajib diisi.',
            'tgl_masuk.date' => 'Tanggal masuk kerja kembali tidak valid.',
            'tgl_masuk.after_or_equal' => 'Tanggal masuk kerja kembali tidak boleh sebelum tanggal selesai cuti.',

            'alasan.required' => 'Alasan cuti wajib diisi.',
            'alasan.max' => 'Alasan cuti maksimal 500 karakter.',

            'status.required' => 'Status pengajuan cuti wajib diisi.',

            'bukti_menikah.mimes' => 'Bukti cuti harus berupa file gambar dengan format JPG, PNG, atau JPEG.',
            'bukti_menikah.max' => 'Ukuran file bukti maksimal 10MB.',
        ]);


        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMenikah::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $buktiMenikah = null;
        if ($request->file('bukti_menikah')) {
            if ($cutis->bukti_menikah) {
                Storage::delete($cutis->bukti_menikah);
            }
            $buktiMenikah = $request->file('bukti_menikah')->store('bukti_menikah');
        } else {
            $buktiMenikah = $cutis->bukti_menikah;
        }

        // Hitung jumlah hari cuti
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahCuti = $tglSelesai->diffInDays($tglMulai) + 1;

        $cutis->update([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'pj_id' => $request->pj_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $jumlahCuti,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'bukti_menikah' => $buktiMenikah,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $cutis->id,
            'aktivitas' => 'Memperbaharui Data Cuti Menikah',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-cutimenikah.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data cuti menikah!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMenikah::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogCutiMenikah::create([
            'cuti_menikah_id' => $cutis->id,
            'aktivitas' => 'Menghapus Data Cuti Menikah',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data cuti menikah!',
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
