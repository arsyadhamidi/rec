@extends('dashboard.layout.master')
@section('title', 'Tambah Status Autentikasi | RSKM Regina Eye Center')
@section('menuDataAutentikasi', 'active')
@section('menuStatusAutentikasi', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Status Autentikasi</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-level.index') }}"
                           class="text-primary">Status Autentikasi</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Status Autentikasi</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-level.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Id  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>ID
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="id"
                                               class="form-control @error('id') is-invalid @enderror"
                                               value="{{ old('id') }}"
                                               placeholder="Masukan id level">
                                        @error('id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Nama Level  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Nama Level
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="namalevel"
                                               class="form-control @error('namalevel') is-invalid @enderror"
                                               value="{{ old('namalevel') }}"
                                               placeholder="Masukan nama level">
                                        @error('namalevel')
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
                            <a href="{{route('admin-level.index')}}" class="btn btn-primary">
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
        });
    </script>
@endpush
