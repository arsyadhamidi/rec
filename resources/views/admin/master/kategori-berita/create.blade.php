@extends('dashboard.layout.master')
@section('title', 'Tambah Data Kategori Berita | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuKategoriBerita', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Data Kategori Berita</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-kategoriberita.index') }}"
                           class="text-primary">Data Kategori Berita</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Data Kategori Berita</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-kategoriberita.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Kategori Berita  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Kategori Berita
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="nm_kategori"
                                               class="form-control @error('nm_kategori') is-invalid @enderror"
                                               value="{{ old('nm_kategori') }}"
                                               placeholder="Masukan nama kategori">
                                        @error('nm_kategori')
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
                            <a href="{{route('admin-kategoriberita.index')}}" class="btn btn-primary">
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
