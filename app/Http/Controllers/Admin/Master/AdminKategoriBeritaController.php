<?php

namespace App\Http\Controllers\Admin\Master;

use App\Exports\KategoriBeritaExport;
use App\Http\Controllers\Controller;
use App\Models\KategoriBerita;
use App\Models\LogKategoriBerita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminKategoriBeritaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = KategoriBerita::select([
                'kategori_berita.id',
                'kategori_berita.nm_kategori',
                'kategori_berita.is_deleted'
            ])
                ->where('kategori_berita.is_deleted', '1');
            $query->orderBy('kategori_berita.id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('kategori_berita.nm_kategori', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-kategoriberita.edit', $item->id ?? '');

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

        return view('admin.master.kategori-berita.index');
    }

    public function generatepdf()
    {
        $query = KategoriBerita::select([
            'kategori_berita.id',
            'kategori_berita.nm_kategori',
            'kategori_berita.is_deleted',
        ])
            ->where('kategori_berita.is_deleted', '1');

        $kategori = $query->orderBy('kategori_berita.id', 'desc')->get();

        $pdf = PDF::loadview('admin.master.kategori-berita.export-pdf', ['kategori' => $kategori])->setPaper('A4', 'potrait');
        return $pdf->stream('data-kategori-berita.pdf');
    }

    public function generateexcel()
    {
        $query = KategoriBerita::select([
                'kategori_berita.id',
                'kategori_berita.nm_kategori',
                'kategori_berita.is_deleted',
            ])
            ->where('kategori_berita.is_deleted', '1');

        $data = $query->orderBy('kategori_berita.id', 'desc')->get();
        return Excel::download(new KategoriBeritaExport($data), 'data-kategori-berita.xlsx');
    }

    public function create()
    {
        return view('admin.master.kategori-berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_kategori' => 'required|max:100'
        ], [
            'nm_kategori.required' => 'Nama Kategori Berita wajib diisi',
            'nm_kategori.max' => 'Nama Kategori Berita maksimal 100 karakter',
        ]);

        $users = Auth()->user();

        $kategoris = KategoriBerita::create([
            'nm_kategori' => $request->nm_kategori,
            'created_at' => Carbon::now(),
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogKategoriBerita::create([
            'kategori_berita_id' => $kategoris->id,
            'aktivitas' => 'Membuat Data Kategori Berita',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-kategoriberita.index')->with('success', 'Anda berhasil membuat data kategori berita');
    }

    public function edit($id)
    {
        $kategoris = KategoriBerita::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();
        return view('admin.master.kategori-berita.edit', [
            'kategoris' => $kategoris,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_kategori' => 'required'
        ], [
            'nm_kategori.required' => 'Nama Spesialis wajib diisi',
        ]);

        $users = Auth()->user();

        KategoriBerita::where('id', $id)->update([
            'nm_kategori' => $request->nm_kategori,
            'updated_at' => Carbon::now(),
            'updated_by' => $users->name,
        ]);

        LogKategoriBerita::create([
            'kategori_berita_id' => $id,
            'aktivitas' => 'Memperbaharui Data KategoriBerita',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-kategoriberita.index')->with('success', 'Anda berhasil memperbaharui data kategori berita');
    }

    public function destroy($id)
    {
        $users = Auth()->user();

        KategoriBerita::where('id', $id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogKategoriBerita::create([
            'kategori_berita_id' => $id,
            'aktivitas' => 'Menghapus Data Kategori Berita',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data kategori berita',
        ]);
    }
}
