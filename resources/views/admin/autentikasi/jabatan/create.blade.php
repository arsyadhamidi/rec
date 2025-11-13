@extends('dashboard.layout.master')
@section('title', 'Tambah Data Jabatan | RSKM Regina Eye Center')
@section('menuDataAutentikasi', 'active')
@section('menuDataJabatan', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Data Jabatan</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-jabatan.index') }}"
                           class="text-primary">Data Jabatan</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Data Jabatan</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-jabatan.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Kode Jabatan  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Kode Jabatan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="kd_jabatan"
                                               class="form-control @error('kd_jabatan') is-invalid @enderror"
                                               value="{{ old('kd_jabatan') }}"
                                               placeholder="Masukan kode jabatan">
                                        @error('kd_jabatan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Nama Jabatan  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Nama Jabatan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="nm_jabatan"
                                               class="form-control @error('nm_jabatan') is-invalid @enderror"
                                               value="{{ old('nm_jabatan') }}"
                                               placeholder="Masukan nama jabatan">
                                        @error('nm_jabatan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{route('admin-jabatan.index')}}" class="btn btn-primary">
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
