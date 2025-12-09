<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Kerjasama;
use App\Models\LogKerjasama;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminKerjasamaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = Kerjasama::where('is_deleted', '1')
                ->orderBy('id', 'desc');


            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nm_kerjasama', 'LIKE', "%{$search}%");
                });
            }

            $totalRecords = $query->count();

            $data = $query->paginate($perPage);

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route("admin-kerjasama.edit", $item->id ?? '');
                $imageUrl = asset('storage/' . $item->foto_kerjasama);

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

        return view('admin.master.kerjasama.index');
    }

    public function create()
    {
        return view('admin.master.kerjasama.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_kerjasama'   => 'required|max:100',
            'keterangan'     => 'required',
            'syarat'         => 'required',
            'foto_kerjasama' => 'required|max:10248|mimes:jpg,jpeg,png',
        ], [
            'nm_kerjasama.required'   => 'Nama kerjasama tidak boleh kosong.',
            'nm_kerjasama.max'        => 'Nama kerjasama maksimal 100 karakter.',

            'keterangan.required'     => 'Keterangan wajib diisi.',

            'syarat.required'         => 'Syarat wajib diisi.',

            'foto_kerjasama.required' => 'Foto kerjasama wajib diunggah.',
            'foto_kerjasama.max'      => 'Ukuran foto kerjasama maksimal 10 MB.',
            'foto_kerjasama.mimes'    => 'Foto kerjasama harus berformat JPG, JPEG, atau PNG.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $fotoAsuransi = null;
        if($request->file('foto_kerjasama')){
            $fotoAsuransi = $request->file('foto_kerjasama')->store('foto_kerjasama');
        }

        $asuransi = Kerjasama::create([
            'nm_kerjasama' => $request->nm_kerjasama,
            'keterangan' => $request->keterangan,
            'syarat' => $request->syarat,
            'foto_kerjasama' => $fotoAsuransi,
            'created_at' => $carbons,
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogKerjasama::create([
            'kerjasama_id' => $asuransi->id,
            'aktivitas' => 'Membuat Data Asuransi Kesehatan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-kerjasama.index')->with('success', 'Selamat ! Anda berhasil membuat data asuransi kesehatan !');
    }

    public function edit($id)
    {
        $kerjas = Kerjasama::where('id', $id)
        ->where('is_deleted', '1')
        ->orderBy('id', 'desc')
        ->first();

        return view('admin.master.kerjasama.edit', [
            'kerjas' => $kerjas,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nm_kerjasama'   => 'required|max:100',
            'keterangan'     => 'required',
            'syarat'         => 'required',
            'foto_kerjasama' => 'nullable|max:10248|mimes:jpg,jpeg,png',
        ], [
            'nm_kerjasama.required'   => 'Nama kerjasama tidak boleh kosong.',
            'nm_kerjasama.max'        => 'Nama kerjasama maksimal 100 karakter.',

            'keterangan.required'     => 'Keterangan wajib diisi.',

            'syarat.required'         => 'Syarat wajib diisi.',

            'foto_kerjasama.max'      => 'Ukuran foto kerjasama maksimal 10 MB.',
            'foto_kerjasama.mimes'    => 'Foto kerjasama harus berformat JPG, JPEG, atau PNG.',
        ]);

        $users = Auth::user();
        $carbons = Carbon::now();
        $fotoAsuransi = null;
        $asuransi = Kerjasama::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();
        if($request->file('foto_kerjasama')){
            if($asuransi->foto_kerjasama){
                Storage::delete($asuransi->foto_kerjasama);
            }
            $fotoAsuransi = $request->file('foto_kerjasama')->store('foto_kerjasama');
        }else{
            $fotoAsuransi = $asuransi->foto_kerjasama;
        }

        $asuransi->update([
            'nm_kerjasama' => $request->nm_kerjasama,
            'keterangan' => $request->keterangan,
            'syarat' => $request->syarat,
            'foto_kerjasama' => $fotoAsuransi,
            'updated_at' => $carbons,
            'updated_by' => $users->name,
        ]);

        LogKerjasama::create([
            'kerjasama_id' => $asuransi->id,
            'aktivitas' => 'Memperbaharui Data Asuransi Kesehatan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return redirect()->route('admin-kerjasama.index')->with('success', 'Selamat ! Anda berhasil memperbaharui data asuransi kesehatan !');
    }

    public function destroy($id)
    {
        $users = Auth::user();
        $carbons = Carbon::now();
        $asuransi = Kerjasama::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();

        $asuransi->update([
            'deleted_at' => $carbons,
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogKerjasama::create([
            'kerjasama_id' => $asuransi->id,
            'aktivitas' => 'Menghapus Data Asuransi Kesehatan',
            'user' => $users->name,
            'tanggal' => $carbons->toDateString(),
            'waktu_dibuat' => $carbons,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Anda berhasil menghapus data asuransi kesehatan!'
        ]);
    }
}
