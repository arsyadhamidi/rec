<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Exports\PulangCepatExport;
use App\Http\Controllers\Controller;
use App\Models\LogPulangCepat;
use App\Models\PulangCepat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminPulangCepatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

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
                $resultid = $item->izin_id ?? '';
                $editUrl = route("admin-pulangcepat.edit", $item->izin_id ?? '');

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

        return view('admin.kehadiran.pulang-cepat.index');
    }

    public function generatepdf(Request $request)
    {
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
            ->where('pulang_cepat.is_deleted', '1')
            ->orderBy('pulang_cepat.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('pulang_cepat.tgl_izin', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('pulang_cepat.status', $request->status);
        }

        $pulangs = $query->orderBy('pulang_cepat.id', 'desc')->get();

        $pdf = PDF::loadview('admin.kehadiran.pulang-cepat.export-pdf', ['pulangs' => $pulangs])->setPaper('A4', 'potrait');
        return $pdf->stream('data-pulang-cepat.pdf');
    }

    public function generateexcel(Request $request)
    {
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
            ->where('pulang_cepat.is_deleted', '1')
            ->orderBy('pulang_cepat.id', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $query->whereBetween('pulang_cepat.tgl_izin', [$start_date, $end_date]);
        }

        if ($request->filled('status')) {
            $query->where('pulang_cepat.status', $request->status);
        }

        $data = $query->orderBy('pulang_cepat.id', 'desc')->get();

        return Excel::download(new PulangCepatExport($data), 'data-pulang-cepat.xlsx');
    }

    public function create()
    {
        return view('admin.kehadiran.pulang-cepat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'tgl_izin' => 'required|date',
            'jam_pulang' => 'required',
            'jam_selesai' => 'required|after:jam_pulang',
            'alasan' => 'required|max:500',
            'status' => 'required',
        ], [
            // 🔹 Pesan error umum
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after' => 'Jam kembali harus setelah jam mulai.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
            ],

            // 🔹 Pesan error khusus setiap field
            'user_id.required' => 'Pegawai wajib dipilih.',
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date' => 'Tanggal izin harus berupa tanggal yang valid.',
            'jam_pulang.required' => 'Jam pulang wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam pulang.',
            'alasan.required' => 'Alasan izin wajib diisi.',
            'alasan.max' => 'Alasan izin maksimal 500 karakter.',
            'status.required' => 'Status pengajuan izin wajib diisi.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();

        $izins = PulangCepat::create([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'tgl_izin' => $request->tgl_izin,
            'jam_pulang' => $request->jam_pulang,
            'jam_selesai' => $request->jam_selesai,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogPulangCepat::create([
            'pulang_cepat_id' => $izins->id,
            'aktivitas' => 'Membuat Data Izin Pulang Cepat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-pulangcepat.index')->with('success', 'Selamat ! Anda berhasil menambahkan data izin pulang cepat!');
    }

    public function edit($id)
    {
        $izins = PulangCepat::join('user as u1', 'pulang_cepat.user_id', '=', 'u1.id')
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
            ->where('pulang_cepat.id', $id)
            ->where('pulang_cepat.is_deleted', '1')
            ->orderBy('pulang_cepat.id', 'desc')
            ->first();
        return view('admin.kehadiran.pulang-cepat.edit', [
            'izins' => $izins,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'atasan_id' => 'required',
            'tgl_izin' => 'required|date',
            'jam_pulang' => 'required',
            'jam_selesai' => 'required|after:jam_pulang',
            'alasan' => 'required|max:500',
            'status' => 'required',
        ], [
            // 🔹 Pesan error umum
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'after' => 'Jam kembali harus setelah jam mulai.',
            'max' => [
                'string' => 'Kolom :attribute maksimal :max karakter.',
            ],

            // 🔹 Pesan error khusus setiap field
            'user_id.required' => 'Pegawai wajib dipilih.',
            'atasan_id.required' => 'Atasan langsung wajib dipilih.',
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date' => 'Tanggal izin harus berupa tanggal yang valid.',
            'jam_pulang.required' => 'Jam Pulang wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam pulang.',
            'alasan.required' => 'Alasan izin wajib diisi.',
            'alasan.max' => 'Alasan izin maksimal 500 karakter.',
            'status.required' => 'Status pengajuan izin wajib diisi.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = PulangCepat::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'user_id' => $request->user_id,
            'atasan_id' => $request->atasan_id,
            'tgl_izin' => $request->tgl_izin,
            'jam_pulang' => $request->jam_pulang,
            'jam_selesai' => $request->jam_selesai,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogPulangCepat::create([
            'pulang_cepat_id' => $id,
            'aktivitas' => 'Memperbaharui Data Izin Pulang cepat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-pulangcepat.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data pulang cepat!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $izins = PulangCepat::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $izins->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogPulangCepat::create([
            'pulang_cepat_id' => $id,
            'aktivitas' => 'Menghapus Data Izin Pulang Cepat',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data pulang cepat!',
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
