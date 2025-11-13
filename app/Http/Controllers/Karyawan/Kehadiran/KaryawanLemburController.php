<?php

namespace App\Http\Controllers\Karyawan\Kehadiran;

use App\Http\Controllers\Controller;
use App\Models\Lembur;
use App\Models\LogLembur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanLemburController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $users = Auth::user();
            $query = Lembur::join('user as u1', 'lembur.user_id', '=', 'u1.id')
                ->join('user as u2', 'lembur.atasan_id', '=', 'u2.id')
                ->select([
                    'lembur.id as lembur_id',
                    'lembur.user_id',
                    'lembur.tgl_mulai',
                    'lembur.tgl_selesai',
                    'lembur.jam_mulai',
                    'lembur.jam_selesai',
                    'lembur.status',
                    'lembur.alasan',
                    'lembur.total',
                    'lembur.is_deleted',
                    'u1.name as nama_karyawan',
                    'u2.name as nama_atasan',
                ])
                ->where('user_id', $users->id)
                ->where('lembur.is_deleted', '1')
                ->orderBy('lembur.id', 'desc');


            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                $query->whereBetween('lembur.tgl_mulai', [$start_date, $end_date]);
            }

            $query->where(function ($query) use ($search) {
                $query->where('u1.name', 'LIKE', "%{$search}%")
                    ->orWhere('u2.name', 'LIKE', "%{$search}%")
                    ->orWhere('lembur.alasan', 'LIKE', "%{$search}%");
            });

            if ($request->has('status') && !empty($request->status)) {
                $query->where('lembur.status', $request->status);
            }

            // Hitung jumlah per status
            $statusCounts = [
                '0' => (clone $query)->where('lembur.status', '0')->count(),
                '1' => (clone $query)->where('lembur.status', '1')->count(),
                '2' => (clone $query)->where('lembur.status', '2')->count(),
                '3' => (clone $query)->where('lembur.status', '3')->count(),
                '4' => (clone $query)->where('lembur.status', '4')->count(),
            ];

            $totalLembur = Lembur::where('user_id', $users->id)
                ->where('is_deleted', '1')
                ->where('status', '4')
                ->whereBetween('tgl_mulai', [$request->start_date, $request->end_date])
                ->sum('total');

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->lembur_id ?? '';

                // Cek status
                if (($item->status ?? 0) == 1) {
                    $editButton = '
            <a href="' . route("karyawan-lembur.edit", $item->lembur_id ?? '') . '" class="btn btn-outline-primary me-1">
                <i class="fas fa-edit"></i>
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
                    $deleteButton = '';
                }

                $item->aksi = $editButton . $deleteButton;

                return $item;
            });



            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $dataWithActions,
                'statusCounts' => $statusCounts,
                'totalLemburAll' => $totalLembur,
            ]);
        }

        return view('karyawan.kehadiran.lembur.index');
    }

    public function create()
    {
        return view('karyawan.kehadiran.lembur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
                    $jamSelesai = Carbon::createFromFormat('H:i', $value);

                    // Jika di hari yang sama, jam selesai harus lebih besar dari jam mulai
                    if ($jamSelesai->lt($jamMulai) && $request->tgl_mulai === $request->tgl_selesai) {
                        $fail('Jam selesai harus lebih besar dari jam mulai pada hari yang sama.');
                    }
                }
            ],
            'alasan' => 'required|max:500',
        ], [
            'atasan_id.required'    => 'Atasan wajib dipilih.',
            'tgl_mulai.required'    => 'Tanggal mulai wajib diisi.',
            'tgl_mulai.date'        => 'Format tanggal mulai tidak valid.',
            'tgl_selesai.required'  => 'Tanggal selesai wajib diisi.',
            'tgl_selesai.date'      => 'Format tanggal selesai tidak valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'jam_mulai.required'    => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus dalam format HH:mm (contoh: 08:00).',
            'jam_selesai.required'  => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus dalam format HH:mm (contoh: 17:00).',
            'alasan.required'       => 'Alasan lembur wajib diisi.',
            'alasan.max'            => 'Alasan lembur maksimal 500 karakter.',
        ]);


        $users = Auth::user();
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesai = Carbon::createFromFormat('H:i', $request->jam_selesai);

        // Logika untuk perhitungan total menit lembur
        if ($request->tgl_mulai === $request->tgl_selesai) {
            if ($jamSelesai->lt($jamMulai)) {
                $jamSelesai->addDay();
            }
            $totalMenit = $jamSelesai->diffInMinutes($jamMulai);
        } else {
            $tglMulai = Carbon::parse($request->tgl_mulai)->setTimeFrom($jamMulai);
            $tglSelesai = Carbon::parse($request->tgl_selesai)->setTimeFrom($jamSelesai);
            $totalMenit = $tglSelesai->diffInMinutes($tglMulai);
        }

        // Simpan total menit lembur
        $lembur = Lembur::create([
            'user_id' => $users->id,
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'total' => $totalMenit,
            'status' => '1',
            'alasan' => $request->alasan,
            'created_at' => Carbon::now(),
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogLembur::create([
            'lembur_id' => $lembur->id,
            'aktivitas' => 'Membuat Data Lembur',
            'user' => $users->name ?? '-',
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('karyawan-lembur.index')->with('success', 'Anda berhasil menambahkan data lembur');
    }

    public function edit($id)
    {
        $lemburs = Lembur::join('user as u1', 'lembur.user_id', '=', 'u1.id')
            ->join('user as u2', 'lembur.atasan_id', '=', 'u2.id')
            ->select([
                'lembur.id as lembur_id',
                'lembur.user_id',
                'lembur.atasan_id',
                'lembur.tgl_mulai',
                'lembur.tgl_selesai',
                'lembur.jam_mulai',
                'lembur.jam_selesai',
                'lembur.status',
                'lembur.alasan',
                'lembur.total',
                'lembur.is_deleted',
                'u1.name as nama_karyawan',
                'u2.name as nama_atasan',
            ])
            ->where('lembur.id', $id)
            ->where('lembur.is_deleted', '1')
            ->first();

        return view('karyawan.kehadiran.lembur.edit', compact('lemburs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atasan_id' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
                    $jamSelesai = Carbon::createFromFormat('H:i', $value);

                    // Jika di hari yang sama, jam selesai harus lebih besar dari jam mulai
                    if ($jamSelesai->lt($jamMulai) && $request->tgl_mulai === $request->tgl_selesai) {
                        $fail('Jam selesai harus lebih besar dari jam mulai pada hari yang sama.');
                    }
                }
            ],
            'alasan' => 'required|max:500',
        ], [
            'atasan_id.required'    => 'Atasan wajib dipilih.',
            'tgl_mulai.required'    => 'Tanggal mulai wajib diisi.',
            'tgl_mulai.date'        => 'Format tanggal mulai tidak valid.',
            'tgl_selesai.required'  => 'Tanggal selesai wajib diisi.',
            'tgl_selesai.date'      => 'Format tanggal selesai tidak valid.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'jam_mulai.required'    => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus dalam format HH:mm (contoh: 08:00).',
            'jam_selesai.required'  => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus dalam format HH:mm (contoh: 17:00).',
            'alasan.required'       => 'Alasan lembur wajib diisi.',
            'alasan.max'            => 'Alasan lembur maksimal 500 karakter.',
        ]);


        $users = Auth::user();
        $lemburs = Lembur::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();
        $jamMulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesai = Carbon::createFromFormat('H:i', $request->jam_selesai);

        // Logika untuk perhitungan total menit lembur
        if ($request->tgl_mulai === $request->tgl_selesai) {
            if ($jamSelesai->lt($jamMulai)) {
                $jamSelesai->addDay();
            }
            $totalMenit = $jamSelesai->diffInMinutes($jamMulai);
        } else {
            $tglMulai = Carbon::parse($request->tgl_mulai)->setTimeFrom($jamMulai);
            $tglSelesai = Carbon::parse($request->tgl_selesai)->setTimeFrom($jamSelesai);
            $totalMenit = $tglSelesai->diffInMinutes($tglMulai);
        }

        // Simpan total menit lembur
        $lemburs->update([
            'atasan_id' => $request->atasan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'total' => $totalMenit,
            'alasan' => $request->alasan,
            'updated_at' => Carbon::now(),
            'updated_by' => $users->name,
        ]);

        LogLembur::create([
            'lembur_id' => $id,
            'aktivitas' => 'Memperbaharui Data Lembur',
            'user' => $users->name ?? '-',
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('karyawan-lembur.index')->with('success', 'Anda berhasil memperbaharui data lembur');
    }

    public function destroy($id)
    {

        $users = Auth::user();
        $lemburs = Lembur::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        // Simpan total menit lembur
        $lemburs->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => $users->id,
            'is_deleted' => '0'
        ]);

        LogLembur::create([
            'lembur_id' => $id,
            'aktivitas' => 'Menghapus Data Lembur',
            'user' => $users->name ?? '-',
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data lembur',
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
