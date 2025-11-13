<?php

namespace App\Http\Controllers\Karyawan\Berita;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\LogBerita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KaryawanBeritaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Berita::join('kategori_berita', 'berita.kategori_berita_id', '=', 'kategori_berita.id')
                ->select([
                    'berita.id',
                    'berita.judul',
                    'berita.slug',
                    'berita.isi_berita',
                    'berita.ringkasan',
                    'berita.gambar_berita',
                    'berita.tgl_berita',
                    'berita.status',
                    'berita.is_deleted',
                    'kategori_berita.nm_kategori',
                ])
                ->where('berita.is_deleted', '1')
                ->orderBy('berita.id', 'desc');


            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('berita.judul', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('berita.status', $request->status);
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route("humas-berita.edit", $item->id ?? '');
                $imageUrl = asset('storage/' . $item->gambar_berita);

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

        return view('karyawan.berita.index');
    }

    public function create()
    {
        $kategoris = KategoriBerita::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        return view('karyawan.berita.create', [
            'kategoris' => $kategoris,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_berita_id' => 'required',
            'judul' => 'required|max:255',
            'slug' => 'required|max:255|unique:berita,slug',
            'isi_berita' => 'required',
            'ringkasan' => 'required|max:500',
            'gambar_berita' => 'required|max:10248|mimes:png,jpg,jpeg',
            'tgl_berita' => 'required|date',
            'status' => 'required',
        ], [
            'kategori_berita_id.required' => 'Kategori berita harus dipilih.',
            'judul.required' => 'Judul berita tidak boleh kosong.',
            'judul.max' => 'Judul berita maksimal 255 karakter.',
            'slug.required' => 'Slug berita wajib diisi.',
            'slug.max' => 'Slug berita maksimal 255 karakter.',
            'slug.unique' => 'Slug berita sudah digunakan, silakan gunakan slug lain.',
            'isi_berita.required' => 'Isi berita tidak boleh kosong.',
            'ringkasan.required' => 'Ringkasan berita wajib diisi.',
            'ringkasan.max' => 'Ringkasan berita maksimal 500 karakter.',
            'gambar_berita.required' => 'Gambar berita wajib diunggah.',
            'gambar_berita.mimes' => 'Format gambar harus berupa JPG, JPEG, atau PNG.',
            'gambar_berita.max' => 'Ukuran gambar tidak boleh lebih dari 10 MB.',
            'tgl_berita.required' => 'Tanggal berita wajib diisi.',
            'tgl_berita.date' => 'Format tanggal berita tidak valid.',
            'status.required' => 'Status berita wajib dipilih.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $tglBerita = Carbon::parse($request->tgl_berita)->setTimeFrom($carbons)->format('Y-m-d H:i:s');

        $gambarBerita = null;
        if ($request->file('gambar_berita')) {
            $gambarBerita = $request->file('gambar_berita')->store('gambar_berita');
        }

        $beritas = Berita::create([
            'kategori_berita_id' => $request->kategori_berita_id,
            'judul' => $request->judul,
            'slug' => $request->slug,
            'isi_berita' => $request->isi_berita,
            'ringkasan' => $request->ringkasan,
            'gambar_berita' => $gambarBerita,
            'tgl_berita' => $tglBerita,
            'status' => $request->status,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1',
        ]);

        LogBerita::create([
            'berita_id' => $beritas->id,
            'aktivitas' => 'Membuat Data Berita',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('humas-berita.index')->with('success', 'Selamat ! Anda berhasil membuat data berita!');
    }

    public function edit($id)
    {
        $kategoris = KategoriBerita::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        $beritas = Berita::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();
        return view('karyawan.berita.edit', [
            'kategoris' => $kategoris,
            'beritas' => $beritas,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_berita_id' => 'required',
            'judul' => 'required|max:255',
            'slug' => 'required|max:255|unique:berita,slug',
            'isi_berita' => 'required',
            'ringkasan' => 'required|max:500',
            'gambar_berita' => 'max:10248|mimes:png,jpg,jpeg',
            'tgl_berita' => 'required|date',
            'status' => 'required',
        ], [
            'kategori_berita_id.required' => 'Kategori berita harus dipilih.',
            'judul.required' => 'Judul berita tidak boleh kosong.',
            'judul.max' => 'Judul berita maksimal 255 karakter.',
            'slug.required' => 'Slug berita wajib diisi.',
            'slug.max' => 'Slug berita maksimal 255 karakter.',
            'slug.unique' => 'Slug berita sudah digunakan, silakan gunakan slug lain.',
            'isi_berita.required' => 'Isi berita tidak boleh kosong.',
            'ringkasan.required' => 'Ringkasan berita wajib diisi.',
            'ringkasan.max' => 'Ringkasan berita maksimal 500 karakter.',
            'gambar_berita.mimes' => 'Format gambar harus berupa JPG, JPEG, atau PNG.',
            'gambar_berita.max' => 'Ukuran gambar tidak boleh lebih dari 10 MB.',
            'tgl_berita.required' => 'Tanggal berita wajib diisi.',
            'tgl_berita.date' => 'Format tanggal berita tidak valid.',
            'status.required' => 'Status berita wajib dipilih.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $tglBerita = Carbon::parse($request->tgl_berita)->setTimeFrom($carbons)->format('Y-m-d H:i:s');
        $beritas = Berita::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $gambarBerita = null;
        if ($request->file('gambar_berita')) {
            if ($beritas->gambar_berita) {
                Storage::delete($beritas->gambar_berita);
            }
            $gambarBerita = $request->file('gambar_berita')->store('gambar_berita');
        } else {
            $gambarBerita = $beritas->gambar_berita;
        }

        $beritas->update([
            'kategori_berita_id' => $request->kategori_berita_id,
            'judul' => $request->judul,
            'slug' => $request->slug,
            'isi_berita' => $request->isi_berita,
            'ringkasan' => $request->ringkasan,
            'gambar_berita' => $gambarBerita,
            'tgl_berita' => $tglBerita,
            'status' => $request->status,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogBerita::create([
            'berita_id' => $id,
            'aktivitas' => 'Memperbaharui Data Berita',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('humas-berita.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data berita!');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $beritas = Berita::where('id', $id)
            ->where('is_deleted', '1')
            ->orderBy('id', 'desc')
            ->first();

        $beritas->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogBerita::create([
            'berita_id' => $id,
            'aktivitas' => 'Menghapus Data Berita',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat ! Anda berhasil menghapus data berita!'
        ]);
    }
}
