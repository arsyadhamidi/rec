<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Exports\CutiMelahirkanExport;
use App\Http\Controllers\Controller;
use App\Models\CutiMelahirkan;
use App\Models\LogCutiDokter;
use App\Models\LogCutiMelahirkan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminCutiMelahirkanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = CutiMelahirkan::join('user as u1', 'cuti_melahirkan.user_id', '=', 'u1.id')
                ->join('user as u2', 'cuti_melahirkan.atasan_id', '=', 'u2.id')
                ->select([
                    'cuti_melahirkan.id as cuti_id',
                    'cuti_melahirkan.user_id',
                    'cuti_melahirkan.atasan_id',
                    'cuti_melahirkan.tgl_mulai',
                    'cuti_melahirkan.tgl_selesai',
                    'cuti_melahirkan.tgl_masuk',
                    'cuti_melahirkan.lama_cuti',
                    'cuti_melahirkan.alasan',
                    'cuti_melahirkan.status',
                    'cuti_melahirkan.bukti_melahirkan',
                    'cuti_melahirkan.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('cuti_melahirkan.is_deleted', '1')
                ->orderBy('cuti_melahirkan.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('cuti_melahirkan.tgl_mulai', [$start_date, $end_date]);
            }

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('u1.name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('cuti_melahirkan.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->cuti_id ?? '';
                $editUrl = route("admin-cutimelahirkan.edit", $item->cuti_id ?? '');
                $imageUrl = asset('storage/' . $item->bukti_melahirkan);

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

        return view('admin.kehadiran.cuti-melahirkan.index');
    }

    public function generatepdf(Request $request)
    {
        $query = CutiMelahirkan::join('user as u1', 'cuti_melahirkan.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_melahirkan.atasan_id', '=', 'u2.id')
            ->select([
                'cuti_melahirkan.id as cuti_id',
                'cuti_melahirkan.user_id',
                'cuti_melahirkan.atasan_id',
                'cuti_melahirkan.tgl_mulai',
                'cuti_melahirkan.tgl_selesai',
                'cuti_melahirkan.tgl_masuk',
                'cuti_melahirkan.lama_cuti',
                'cuti_melahirkan.alasan',
                'cuti_melahirkan.status',
                'cuti_melahirkan.bukti_melahirkan',
                'cuti_melahirkan.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('cuti_melahirkan.is_deleted', '1')
            ->orderBy('cuti_melahirkan.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_melahirkan.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_melahirkan.status', $request->status);
        }

        $cutis = $query->orderBy('cuti_melahirkan.id', 'desc')->get();

        $pdf = PDF::loadview('admin.kehadiran.cuti-melahirkan.export-pdf', ['cutis' => $cutis])->setPaper('A4', 'potrait');
        return $pdf->stream('data-cuti-melahirkan.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = CutiMelahirkan::join('user as u1', 'cuti_melahirkan.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_melahirkan.atasan_id', '=', 'u2.id')
            ->select([
                'cuti_melahirkan.id as cuti_id',
                'cuti_melahirkan.user_id',
                'cuti_melahirkan.atasan_id',
                'cuti_melahirkan.tgl_mulai',
                'cuti_melahirkan.tgl_selesai',
                'cuti_melahirkan.tgl_masuk',
                'cuti_melahirkan.lama_cuti',
                'cuti_melahirkan.alasan',
                'cuti_melahirkan.status',
                'cuti_melahirkan.bukti_melahirkan',
                'cuti_melahirkan.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('cuti_melahirkan.is_deleted', '1')
            ->orderBy('cuti_melahirkan.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('cuti_melahirkan.tgl_mulai', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('cuti_melahirkan.status', $request->status);
        }

        $data = $query->orderBy('cuti_melahirkan.id', 'desc')->get();

        return Excel::download(new CutiMelahirkanExport($data), 'data-cuti-melahirkan.xlsx');
    }

    public function create()
    {
        return view('admin.kehadiran.cuti-melahirkan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tgl_masuk' => 'required|date|after_or_equal:tgl_selesai',
            'alasan' => 'required|max:500',
            'status' => 'required',
            'bukti_melahirkan' => 'required|mimes:jpg,png,jpeg|max:10248',
        ], [
            'user_id.required' => 'Pegawai wajib dipilih.',
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',

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

            'bukti_melahirkan.required' => 'Bukti cuti melahirkan wajib diunggah.',
            'bukti_melahirkan.mimes' => 'Bukti cuti harus berupa file gambar dengan format JPG, PNG, atau JPEG.',
            'bukti_melahirkan.max' => 'Ukuran file bukti maksimal 10MB.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $buktiMelahirkan = null;
        if ($request->file('bukti_melahirkan')) {
            $buktiMelahirkan = $request->file('bukti_melahirkan')->store('bukti_melahirkan');
        }

        // Hitung jumlah hari cuti
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahCuti = $tglSelesai->diffInDays($tglMulai) + 1;

        $cutis = CutiMelahirkan::create([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $jumlahCuti,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'bukti_melahirkan' => $buktiMelahirkan,
            'tahun' => $carbons->format('Y'),
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogCutiMelahirkan::create([
            'cuti_melahirkan_id' => $cutis->id,
            'aktivitas' => 'Membuat Data Cuti Melahirkan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-cutimelahirkan.index')->with('success', 'Selamat ! Anda berhasil menambahkan data cuti melahirkan!');
    }

    public function edit($id)
    {
        $cutis = CutiMelahirkan::join('user as u1', 'cuti_melahirkan.user_id', '=', 'u1.id')
            ->join('user as u2', 'cuti_melahirkan.atasan_id', '=', 'u2.id')
            ->select([
                'cuti_melahirkan.id as cuti_id',
                'cuti_melahirkan.user_id',
                'cuti_melahirkan.atasan_id',
                'cuti_melahirkan.tgl_mulai',
                'cuti_melahirkan.tgl_selesai',
                'cuti_melahirkan.tgl_masuk',
                'cuti_melahirkan.lama_cuti',
                'cuti_melahirkan.alasan',
                'cuti_melahirkan.status',
                'cuti_melahirkan.bukti_melahirkan',
                'cuti_melahirkan.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('cuti_melahirkan.id', $id)
            ->where('cuti_melahirkan.is_deleted', '1')
            ->orderBy('cuti_melahirkan.id', 'desc')
            ->first();
        return view('admin.kehadiran.cuti-melahirkan.edit', [
            'cutis' => $cutis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tgl_masuk' => 'required|date|after_or_equal:tgl_selesai',
            'alasan' => 'required|max:500',
            'status' => 'required',
            'bukti_melahirkan' => 'mimes:jpg,png,jpeg|max:10248',
        ], [
            'user_id.required' => 'Pegawai wajib dipilih.',
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',

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

            'bukti_melahirkan.mimes' => 'Bukti cuti harus berupa file gambar dengan format JPG, PNG, atau JPEG.',
            'bukti_melahirkan.max' => 'Ukuran file bukti maksimal 10MB.',
        ]);


        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMelahirkan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $buktiMelahirkan = null;
        if ($request->file('bukti_melahirkan')) {
            if ($cutis->bukti_melahirkan) {
                Storage::delete($cutis->bukti_melahirkan);
            }
            $buktiMelahirkan = $request->file('bukti_melahirkan')->store('bukti_melahirkan');
        } else {
            $buktiMelahirkan = $cutis->bukti_melahirkan;
        }

        // Hitung jumlah hari cuti
        $tglMulai = Carbon::parse($request->tgl_mulai);
        $tglSelesai = Carbon::parse($request->tgl_selesai);
        $jumlahCuti = $tglSelesai->diffInDays($tglMulai) + 1;

        $cutis->update([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'tgl_masuk' => $request->tgl_masuk,
            'lama_cuti' => $jumlahCuti,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'bukti_melahirkan' => $buktiMelahirkan,
            'tahun' => $carbons->format('Y'),
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogCutiMelahirkan::create([
            'cuti_melahirkan_id' => $cutis->id,
            'aktivitas' => 'Memperbaharui Data Cuti Melahirkan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-cutimelahirkan.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data cuti melahirkan!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $cutis = CutiMelahirkan::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $cutis->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogCutiMelahirkan::create([
            'cuti_melahirkan_id' => $cutis->id,
            'aktivitas' => 'Menghapus Data Cuti Melahirkan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data cuti melahirkan!',
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
