@extends('dashboard.layout.master')
@section('title', 'Edit Data Spesialis | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuSpesialis', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data Spesialis</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-spesialis.index') }}"
                           class="text-primary">Data Spesialis</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Data Spesialis</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-spesialis.update', $spesialis->id ?? '') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Nama Spesialis  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Nama Spesialis
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="nama_spesialis"
                                               class="form-control @error('nama_spesialis') is-invalid @enderror"
                                               value="{{ old('nama_spesialis', $spesialis->nama_spesialis ?? '-') }}"
                                               placeholder="Masukan nama spesialis">
                                        @error('nama_spesialis')
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
                            <a href="{{route('admin-spesialis.index')}}" class="btn btn-primary">
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
