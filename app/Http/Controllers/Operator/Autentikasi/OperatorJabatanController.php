<?php

namespace App\Http\Controllers\Operator\Autentikasi;

use PDF;
use App\Exports\JabatanExport;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\LogJabatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OperatorJabatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Jabatan::select([
                'jabatan.id',
                'jabatan.nm_jabatan',
                'jabatan.kd_jabatan',
                'jabatan.is_deleted'
            ])
                ->where('jabatan.is_deleted', '1');
            $query->orderBy('jabatan.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('jabatan.nm_jabatan', 'LIKE', "%{$search}%")
                        ->Where('jabatan.kd_jabatan', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('operator-jabatan.edit', $item->id ?? '');

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

        return view('operator.autentikasi.jabatan.index');
    }

    public function generatepdf()
    {
        $query = Jabatan::select([
            'jabatan.id',
            'jabatan.kd_jabatan',
            'jabatan.nm_jabatan',
            'jabatan.is_deleted',
        ])
            ->where('jabatan.is_deleted', '1');

        $jabatan = $query->orderBy('jabatan.id', 'desc')->get();

        $pdf = PDF::loadview('operator.autentikasi.jabatan.export-pdf', ['jabatan' => $jabatan])->setPaper('A4', 'potrait');
        return $pdf->stream('data-jabatan.pdf');
    }

    public function generateexcel()
    {
        $query = Jabatan::select([
            'jabatan.id',
            'jabatan.kd_jabatan',
            'jabatan.nm_jabatan',
            'jabatan.is_deleted',
        ])
            ->where('jabatan.is_deleted', '1');

        $data = $query->orderBy('jabatan.id', 'desc')->get();
        return Excel::download(new JabatanExport($data), 'data-jabatan.xlsx');
    }

    public function create()
    {
        return view('operator.autentikasi.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_jabatan' => 'required',
            'nm_jabatan' => 'required|max:100',
        ], [
            'kd_jabatan.required' => 'Kode Jabatan wajib diisi',
            'nm_jabatan.required' => 'Nama Jabatan wajib diisi',
            'nm_jabatan.max' => 'Nama Jabatan maksimal 100 karakter',
        ]);

        $users = Auth()->user();
        $carbons = Carbon::now();

        $jabatan = Jabatan::create([
            'kd_jabatan' => $request->kd_jabatan,
            'nm_jabatan' => $request->nm_jabatan,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogJabatan::create([
            'jabatan_id' => $jabatan->id,
            'aktivitas' => 'Membuat Data Jabatan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('operator-jabatan.index')->with('success', 'Anda berhasil membuat data jabatan');
    }

    public function edit($id)
    {
        $jabatan = Jabatan::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();
        return view('operator.autentikasi.jabatan.edit', [
            'jabatan' => $jabatan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kd_jabatan' => 'required',
            'nm_jabatan' => 'required|max:100',
        ], [
            'kd_jabatan.required' => 'Kode Jabatan wajib diisi',
            'nm_jabatan.required' => 'Nama Jabatan wajib diisi',
            'nm_jabatan.max' => 'Nama Jabatan maksimal 100 karakter',
        ]);

        $users = Auth()->user();
        $carbons = Carbon::now();

        Jabatan::where('id', $id)->update([
            'kd_jabatan' => $request->kd_jabatan,
            'nm_jabatan' => $request->nm_jabatan,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogJabatan::create([
            'jabatan_id' => $id,
            'aktivitas' => 'Memperbaharui Data Jabatan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('operator-jabatan.index')->with('success', 'Anda berhasil memperbaharui data jabatan');
    }

    public function destroy($id)
    {
        $users = Auth()->user();
        $carbons = Carbon::now();

        Jabatan::where('id', $id)->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogJabatan::create([
            'jabatan_id' => $id,
            'aktivitas' => 'Menghapus Data Jabatan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data jabatan',
        ]);
    }
}
