<?php

namespace App\Http\Controllers\Admin\Master;

use App\Exports\SpesialisExport;
use App\Http\Controllers\Controller;
use App\Models\LogSpesialis;
use App\Models\Spesialis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminSpesialisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Spesialis::select([
                'spesialis.id',
                'spesialis.nama_spesialis',
                'spesialis.is_deleted'
            ])
                ->where('spesialis.is_deleted', '1');
            $query->orderBy('spesialis.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('spesialis.nama_spesialis', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-spesialis.edit', $item->id ?? '');

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
                'draw' => $request->input('draw'), // Ambil nomor draw dari permintaan
                'recordsTotal' => $totalRecords, // Kirim jumlah total data
                'recordsFiltered' => $totalRecords, // Jumlah data yang difilter sama dengan jumlah total
                'data' => $dataWithActions, // Kirim data yang sesuai dengan halaman dan jumlah per halaman
            ]);
        }

        return view('admin.master.spesialis.index');
    }

    public function generatepdf()
    {
        $query = Spesialis::select([
            'spesialis.id',
            'spesialis.nama_spesialis',
            'spesialis.is_deleted',
        ])
            ->where('spesialis.is_deleted', '1');

        $spesialis = $query->orderBy('spesialis.id', 'desc')->get();

        $pdf = PDF::loadview('admin.master.spesialis.export-pdf', ['spesialis' => $spesialis])->setPaper('A4', 'potrait');
        return $pdf->stream('data-spesialis.pdf');
    }

    public function generateexcel()
    {
        $query = Spesialis::select([
                'spesialis.id',
                'spesialis.nama_spesialis',
                'spesialis.is_deleted',
            ])
            ->where('spesialis.is_deleted', '1');

        $data = $query->orderBy('spesialis.id', 'desc')->get();
        return Excel::download(new SpesialisExport($data), 'data-spesialis.xlsx');
    }

    public function create()
    {
        return view('admin.master.spesialis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_spesialis' => 'required|max:30'
        ], [
            'nama_spesialis.required' => 'Nama Spesialis wajib diisi',
            'nama_spesialis.max' => 'Nama Spesialis maksimal 30 karakter',
        ]);

        $users = Auth()->user();

        $spesialis = Spesialis::create([
            'nama_spesialis' => $request->nama_spesialis,
            'created_at' => Carbon::now(),
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogSpesialis::create([
            'spesialis_id' => $spesialis->id,
            'aktivitas' => 'Membuat Data Spesialis',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-spesialis.index')->with('success', 'Anda berhasil membuat data spesialis');
    }

    public function edit($id)
    {
        $spesialis = Spesialis::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();
        return view('admin.master.spesialis.edit', [
            'spesialis' => $spesialis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_spesialis' => 'required'
        ], [
            'nama_spesialis.required' => 'Nama Spesialis wajib diisi',
        ]);

        $users = Auth()->user();

        Spesialis::where('id', $id)->update([
            'nama_spesialis' => $request->nama_spesialis,
            'updated_at' => Carbon::now(),
            'updated_by' => $users->name,
        ]);

        LogSpesialis::create([
            'spesialis_id' => $id,
            'aktivitas' => 'Memperbaharui Data Spesialis',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-spesialis.index')->with('success', 'Anda berhasil memperbaharui data spesialis');
    }

    public function destroy($id)
    {
        $users = Auth()->user();

        Spesialis::where('id', $id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogSpesialis::create([
            'spesialis_id' => $id,
            'aktivitas' => 'Menghapus Data Spesialis',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data spesialis',
        ]);
    }
}
