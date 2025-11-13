<?php

namespace App\Http\Controllers\Admin\Autentikasi;

use App\Exports\LevelExport;
use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\LogLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminLevelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Level::select([
                'level.id',
                'level.id',
                'level.namalevel',
                'level.is_deleted'
            ])
                ->where('level.is_deleted', '1');
            $query->orderBy('level.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('level.namalevel', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-level.edit', $item->id ?? '');

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

        return view('admin.autentikasi.level.index');
    }

    public function generatepdf()
    {
        $query = Level::select([
                'level.id',
                'level.namalevel',
                'level.is_deleted',
            ])
            ->where('level.is_deleted', '1');

        $level = $query->orderBy('level.id', 'desc')->get();

        $pdf = PDF::loadview('admin.autentikasi.level.export-pdf', ['level' => $level])->setPaper('A4', 'potrait');
        return $pdf->stream('data-autentikasi.pdf');
    }

    public function generateexcel()
    {
        $query = Level::select([
                'level.id',
                'level.namalevel',
                'level.is_deleted',
            ])
            ->where('level.is_deleted', '1');

        $data = $query->orderBy('level.id', 'desc')->get();
        return Excel::download(new LevelExport($data), 'data-autentikasi.xlsx');
    }

    public function create()
    {
        return view('admin.autentikasi.level.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:level,id',
            'namalevel' => 'required',
        ], [
            'id.required' => 'ID Autentikasi wajib diisi',
            'id.unique' => 'ID Autentikasi sudah tersedia',
            'namalevel.required' => 'Status Autentikasi wajib diisi',
        ]);

        $users = Auth()->user();

        Level::create([
            'id' => $request->id,
            'namalevel' => $request->namalevel,
            'created_at' => Carbon::now(),
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogLevel::create([
            'level_id' => $request->id,
            'aktivitas' => 'Membuat Status Autentikasi',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-level.index')->with('success', 'Anda berhasil menambahkan Data Status Autentikasi');
    }

    public function edit($id)
    {
        $levels = Level::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();
        return view('admin.autentikasi.level.edit', [
            'levels' => $levels,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|unique:level,id',
            'namalevel' => 'required',
        ], [
            'id.required' => 'ID Autentikasi wajib diisi',
            'id.unique' => 'ID Autentikasi sudah tersedia',
            'namalevel.required' => 'Status Autentikasi wajib diisi',
        ]);

        $users = Auth()->user();

        Level::where('id', $id)->update([
            'id' => $request->id,
            'namalevel' => $request->namalevel,
            'updated_at' => Carbon::now(),
            'updated_by' => $users->name,
        ]);

        LogLevel::create([
            'level_id' => $request->id,
            'aktivitas' => 'Memperbaharui Status Autentikasi',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-level.index')->with('success', 'Anda berhasil memperbaharui Data Status Autentikasi');
    }

    public function destroy($id)
    {
        $users = Auth()->user();
        Level::where('id', $id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogLevel::create([
            'level_id' => $id,
            'aktivitas' => 'Menghapus Status Autentikasi',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus Data Status Autentikasi',
        ]);
    }
}
