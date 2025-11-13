<?php

namespace App\Http\Controllers\Admin\Autentikasi;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Level;
use App\Models\LogUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->input('length', 10);
            $search = $request->input('search', '');

            $query = User::join('level', 'user.level_id', 'level.id')
                ->leftJoin('jabatan', 'user.jabatan_id', 'jabatan.id')
                ->select([
                    'user.id',
                    'user.name',
                    'user.username',
                    'user.duplicate',
                    'user.is_deleted',
                    'level.namalevel',
                    'jabatan.nm_jabatan',
                ])
                ->where('user.is_deleted', '1');
            $query->orderBy('id', 'desc');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->Where('user.name', 'LIKE', "%{$search}%")
                        ->orWhere('user.username', 'LIKE', "%{$search}%");
                });
            }

            if ($request->has('level_id') && !empty($request->level_id)) {
                $query->where('user.level_id', $request->level_id);
            }

            if ($request->has('jabatan_id') && !empty($request->jabatan_id)) {
                $query->where('user.jabatan_id', $request->jabatan_id);
            }

            $totalRecords = $query->count(); // Hitung total data

            $data = $query->paginate($perPage); // Gunakan paginate() untuk membagi data sesuai dengan halaman dan jumlah per halaman

            // Tambahkan kolom aksi
            $dataWithActions = $data->map(function ($item) {
                $resultid = $item->id ?? '';
                $editUrl = route('admin-users.edit', $item->id ?? '');

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

        $levels = Level::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        $jabatans = Jabatan::where('is_deleted', '1')->orderBy('id', 'asc')->get();
        return view('admin.autentikasi.users.index', [
            'levels' => $levels,
            'jabatans' => $jabatans,
        ]);
    }

    public function generatepdf(Request $request)
    {
        $query = User::leftJoin('level', 'user.level_id', 'level.id')
            ->leftJoin('jabatan', 'user.jabatan_id', 'jabatan.id')
            ->select([
                'user.id',
                'user.jabatan_id',
                'user.level_id',
                'user.name',
                'user.username',
                'user.email',
                'user.duplicate',
                'user.telp',
                'level.namalevel',
                'jabatan.nm_jabatan',
            ])
            ->where('user.is_deleted', '1');

        if ($request->filled('level_id')) {
            $query->where('user.level_id', $request->level_id);
        }

        if ($request->filled('jabatan_id')) {
            $query->where('user.jabatan_id', $request->jabatan_id);
        }

        $users = $query->orderBy('user.id', 'desc')->get();

        $pdf = PDF::loadview('admin.autentikasi.users.export-pdf', ['users' => $users])->setPaper('A4', 'potrait');
        return $pdf->stream('data-user.pdf');
    }

    public function generateexcel(Request $request)
    {
        $query = User::leftJoin('level', 'user.level_id', 'level.id')
            ->leftJoin('jabatan', 'user.jabatan_id', 'jabatan.id')
            ->select([
                'user.id',
                'user.jabatan_id',
                'user.level_id',
                'user.name',
                'user.username',
                'user.email',
                'user.duplicate',
                'user.telp',
                'level.namalevel',
                'jabatan.nm_jabatan',
            ])
            ->where('user.is_deleted', '1');


        if ($request->filled('level_id')) {
            $query->where('user.level_id', $request->level_id);
        }

        if ($request->filled('jabatan_id')) {
            $query->where('user.jabatan_id', $request->jabatan_id);
        }

        $data = $query->orderBy('user.id', 'desc')->get();
        return Excel::download(new UserExport($data), 'data-user.xlsx');
    }

    public function create()
    {
        $levels = Level::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        $jabatans = Jabatan::where('is_deleted', '1')->orderBy('id', 'asc')->get();

        return view('admin.autentikasi.users.create', [
            'levels' => $levels,
            'jabatans' => $jabatans,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:user,username',
            'email' => 'required',
            'level_id' => 'required',
            'jabatan_id' => 'required',
            'telp' => 'required',
        ], [
            'name.required' => 'Nama Lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah tersedia',
            'email.required' => 'Email Address wajib diisi',
            'level_id.required' => 'Status Autentikasi wajib diisi',
            'jabatan_id.required' => 'Jabatan wajib diisi',
            'telp.required' => 'Nomor Telepon wajib diisi',
        ]);

        $users = Auth()->user();

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt('12345678'),
            'duplicate' => '12345678',
            'level_id' => $request->level_id,
            'jabatan_id' => $request->jabatan_id,
            'telp' => $request->telp,
            'created_at' => Carbon::now(),
            'created_by' => $users->name,
            'is_deleted' => '1'
        ]);

        LogUser::create([
            'user_id' => $user->id,
            'aktivitas' => 'Membuat User Registrasi',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Anda berhasil membuat data user registrasi baru!');
    }

    public function edit($id)
    {
        $levels = Level::where('is_deleted', '1')->orderBy('id', 'desc')->get();
        $jabatans = Jabatan::where('is_deleted', '1')->orderBy('id', 'asc')->get();
        $users = User::where('id', $id)->where('is_deleted', '1')->orderBy('id', 'desc')->first();
        return view('admin.autentikasi.users.edit', [
            'levels' => $levels,
            'jabatans' => $jabatans,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'level_id' => 'required',
            'jabatan_id' => 'required',
            'telp' => 'required',
        ], [
            'name.required' => 'Nama Lengkap wajib diisi',
            'email.required' => 'Email Address wajib diisi',
            'level_id.required' => 'Status Autentikasi wajib diisi',
            'jabatan_id.required' => 'Jabatan wajib diisi',
            'telp.required' => 'Nomor Telepon wajib diisi',
        ]);

        $users = Auth()->user();

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'level_id' => $request->level_id,
            'jabatan_id' => $request->jabatan_id,
            'telp' => $request->telp,
            'updated_at' => Carbon::now(),
            'updated_by' => $users->name,
        ]);

        LogUser::create([
            'user_id' => $id,
            'aktivitas' => 'Memperbaharui User Registrasi',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);

        return redirect()->route('admin-users.index')->with('success', 'Anda berhasil memperbaharui data user registrasi baru!');
    }

    public function destroy($id)
    {
        $users = Auth()->user();
        User::where('id', $id)->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => $users->name,
            'is_deleted' => '0'
        ]);

        LogUser::create([
            'user_id' => $id,
            'aktivitas' => 'Menghapus User Registrasi',
            'user' => $users->name,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_dibuat' => Carbon::now(),
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Anda berhasil menghapus data user registrasi baru!'
        ]);
    }

}
