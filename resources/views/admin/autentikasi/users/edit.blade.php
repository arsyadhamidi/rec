@extends('dashboard.layout.master')
@section('title', 'Edit Data User | RSKM Regina Eye Center')
@section('menuDataAutentikasi', 'active')
@section('menuDataUserRegistrasi', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data User</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-users.index') }}"
                           class="text-primary">Data User</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Data User</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-users.update', $users->id ?? '') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Nama Lengkap  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Nama Lengkap
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $users->name ?? '-') }}"
                                               placeholder="Masukan nama lengkap">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Email  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Email Address
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="email"
                                               name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $users->email ?? '-') }}"
                                               placeholder="Masukan email address">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Level  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Status Autentikasi
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="level_id"
                                                class="form-control @error('level_id') is-invalid @enderror"
                                                id="selectedLevel"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Status Autentikasi</option>
                                            @foreach ($levels as $data)
                                                <option value="{{ $data->id }}"
                                                        {{ $data->id == old('level_id', $users->level_id) ? 'selected' : '' }}>
                                                    {{ $data->namalevel ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('level_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jabatan  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Jabatan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="jabatan_id"
                                                class="form-control @error('jabatan_id') is-invalid @enderror"
                                                id="selectedJabatan"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Jabatan</option>
                                            @foreach ($jabatans as $data)
                                                <option value="{{ $data->id }}"
                                                        {{ $data->id == old('jabatan_id', $users->jabatan_id) ? 'selected' : '' }}>
                                                    {{ $data->nm_jabatan ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('jabatan_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Telepon  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Nomor Telepon
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="telp"
                                               class="form-control @error('telp') is-invalid @enderror"
                                               value="{{ old('telp', $users->telp ?? '-') }}"
                                               placeholder="Masukan nomor telepon">
                                        @error('telp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{ route('admin-users.index') }}"
                               class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            $('#selectedLevel').select2({
                theme: 'bootstrap4',
            });
            $('#selectedJabatan').select2({
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush
