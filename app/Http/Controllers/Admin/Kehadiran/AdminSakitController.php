<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Exports\SakitExport;
use App\Http\Controllers\Controller;
use App\Models\LogSakit;
use App\Models\Sakit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminSakitController extends Controller
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
                $editUrl = route("admin-sakit.edit", $item->sakit_id ?? '');
                $imageUrl = asset('storage/' . $item->bukti_sakit);

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

        return view('admin.kehadiran.sakit.index');
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

        $pdf = PDF::loadview('admin.kehadiran.sakit.export-pdf', ['sakits' => $sakits])->setPaper('A4', 'potrait');
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

    public function create()
    {
        return view('admin.kehadiran.sakit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|max:500',
            'status' => 'required',
            'bukti_sakit' => 'required|mimes:png,jpg,jpeg|max:10248',
        ], [
            'user_id.required' => 'Karyawan wajib dipilih.',
            'atasan_id.required' => 'Atasan wajib dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai sakit wajib diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',
            'tgl_selesai.required' => 'Tanggal selesai sakit wajib diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.required' => 'Alasan sakit wajib diisi.',
            'alasan.max' => 'Alasan sakit maksimal 500 karakter.',
            'status.required' => 'Status pengajuan wajib diisi.',
            'bukti_sakit.required' => 'Bukti surat sakit wajib diunggah.',
            'bukti_sakit.mimes' => 'Bukti surat sakit harus berupa file dengan format PNG, JPG, atau JPEG.',
            'bukti_sakit.max' => 'Ukuran file bukti sakit maksimal 10 MB.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $buktiSakit = null;
        if ($request->file('bukti_sakit')) {
            $buktiSakit = $request->file('bukti_sakit')->store('bukti_sakit');
        }

        $sakits = Sakit::create([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'bukti_sakit' => $buktiSakit,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1',
        ]);


        LogSakit::create([
            'sakit_id' => $sakits->id,
            'aktivitas' => 'Membuat Data Sakit',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-sakit.index')->with('success', 'Selamat ! Anda berhasil membuat data izin sakit');
    }

    public function edit($id)
    {
        $sakits = Sakit::join('user as u1', 'sakit.user_id', '=', 'u1.id')
            ->join('user as u2', 'sakit.atasan_id', '=', 'u2.id')
            ->select([
                'sakit.id as sakit_id',
                'sakit.user_id',
                'sakit.atasan_id',
                'sakit.tgl_mulai',
                'sakit.tgl_selesai',
                'sakit.alasan',
                'sakit.status',
                'sakit.bukti_sakit',
                'sakit.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('sakit.id', $id)
            ->where('sakit.is_deleted', '1')
            ->orderBy('sakit.id', 'desc')
            ->first();
        return view('admin.kehadiran.sakit.edit', [
            'sakits' => $sakits,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|max:500',
            'status' => 'required',
            'bukti_sakit' => 'mimes:png,jpg,jpeg|max:10248',
        ], [
            'user_id.required' => 'Karyawan wajib dipilih.',
            'atasan_id.required' => 'Atasan wajib dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai sakit wajib diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',
            'tgl_selesai.required' => 'Tanggal selesai sakit wajib diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.required' => 'Alasan sakit wajib diisi.',
            'alasan.max' => 'Alasan sakit maksimal 500 karakter.',
            'status.required' => 'Status pengajuan wajib diisi.',
            'bukti_sakit.mimes' => 'Bukti surat sakit harus berupa file dengan format PNG, JPG, atau JPEG.',
            'bukti_sakit.max' => 'Ukuran file bukti sakit maksimal 10 MB.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $sakits = Sakit::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $buktiSakit = null;
        if ($request->file('bukti_sakit')) {
            if ($sakits->bukti_sakit) {
                Storage::delete($sakits->bukti_sakit);
            }
            $buktiSakit = $request->file('bukti_sakit')->store('bukti_sakit');
        } else {
            $buktiSakit = $sakits->bukti_sakit;
        }

        $sakits->update([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'bukti_sakit' => $buktiSakit,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);


        LogSakit::create([
            'sakit_id' => $id,
            'aktivitas' => 'Memperbaharui Data Sakit',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-sakit.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data izin sakit');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();

        $sakits = Sakit::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $sakits->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);


        LogSakit::create([
            'sakit_id' => $id,
            'aktivitas' => 'Menghapus Data Sakit',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data izin sakit',
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
