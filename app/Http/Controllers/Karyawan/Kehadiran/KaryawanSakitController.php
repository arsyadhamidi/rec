<?php

namespace App\Http\Controllers\Karyawan\Kehadiran;

use App\Http\Controllers\Controller;
use App\Models\LogSakit;
use App\Models\Sakit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanSakitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = Sakit::join('user as u1', 'sakit.user_id', '=', 'u1.id')
                ->join('user as u2', 'sakit.atasan_id', '=', 'u2.id')
                ->select([
                    'sakit.id as sakit_id',
                    'sakit.user_id',
                    'sakit.tgl_mulai',
                    'sakit.tgl_selesai',
                    'sakit.alasan',
                    'sakit.status',
                    'sakit.bukti_sakit',
                    'sakit.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('sakit.user_id', $users->id)
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

            // Hitung jumlah per status
            $statusCounts = [
                '0' => (clone $query)->where('sakit.status', '0')->count(),
                '1' => (clone $query)->where('sakit.status', '1')->count(),
                '2' => (clone $query)->where('sakit.status', '2')->count(),
                '3' => (clone $query)->where('sakit.status', '3')->count(),
                '4' => (clone $query)->where('sakit.status', '4')->count(),
            ];

            $totalSakits = Sakit::where('user_id', $users->id)
                ->where('is_deleted', '1')
                ->where('status', '4')
                ->whereBetween('tgl_mulai', [$request->start_date, $request->end_date])
                ->count();

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->sakit_id ?? '';
                $editUrl = route("karyawan-sakit.edit", $item->sakit_id ?? '');
                $imageUrl = asset('storage/' . $item->bukti_sakit);

                // Cek status
                if (($item->status ?? 0) == 1) {
                    $editButton = '
            <a href="' . $editUrl . '" class="btn btn-outline-primary me-1">
                <i class="fas fa-edit"></i>
            </a>
        ';
                    $viewImageButton = '
            <a href="' . $imageUrl . '" class="btn btn-outline-warning mx-1" target="_blank">
                <i class="fas fa-image"></i>
            </a>
        ';
                    $deleteButton = '
            <button type="button"
                    class="btn btn-outline-danger btn-delete"
                    data-resultid="' . e($resultid) . '">
                <i class="fas fa-trash-alt"></i>
            </button>
        ';
                } else {
                    $editButton = '<span class="badge badge-secondary">Tidak Bisa Diperbaharui</span>';
                    $viewImageButton = '';
                    $deleteButton = '';
                }

                $item->aksi = $editButton . $viewImageButton . $deleteButton;

                return $item;
            });


            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $dataWithActions,
                'statusCounts' => $statusCounts,
                'totalSakits' => $totalSakits,
            ]);
        }

        return view('karyawan.kehadiran.sakit.index');
    }

    public function create()
    {
        return view('karyawan.kehadiran.sakit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|max:500',
            'bukti_sakit' => 'required|mimes:png,jpg,jpeg|max:10248',
        ], [
            'atasan_id.required' => 'Atasan wajib dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai sakit wajib diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',
            'tgl_selesai.required' => 'Tanggal selesai sakit wajib diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.required' => 'Alasan sakit wajib diisi.',
            'alasan.max' => 'Alasan sakit maksimal 500 karakter.',
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
            'user_id' => $users->id,
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
            'status' => '1',
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

        return redirect()->route('karyawan-sakit.index')->with('success', 'Selamat ! Anda berhasil membuat data izin sakit');
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
        return view('karyawan.kehadiran.sakit.edit', [
            'sakits' => $sakits,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|max:500',
            'bukti_sakit' => 'mimes:png,jpg,jpeg|max:10248',
        ], [
            'atasan_id.required' => 'Atasan wajib dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai sakit wajib diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',
            'tgl_selesai.required' => 'Tanggal selesai sakit wajib diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.required' => 'Alasan sakit wajib diisi.',
            'alasan.max' => 'Alasan sakit maksimal 500 karakter.',
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
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
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

        return redirect()->route('karyawan-sakit.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data izin sakit');
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
