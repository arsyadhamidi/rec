@extends('dashboard.layout.master')
@section('title', 'Tambah Asuransi Kesehatan | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuKerjasama', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Asuransi Kesehatan</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-kerjasama.index') }}"
                           class="text-primary">Asuransi Kesehatan</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Asuransi Kesehatan</li>
                </ol>
            </nav>
        </div>

        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-kerjasama.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Nama Asuransi  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Nama Asuransi
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="nm_kerjasama"
                                               class="form-control @error('nm_kerjasama') is-invalid @enderror"
                                               value="{{ old('nm_kerjasama') }}"
                                               placeholder="Masukan keterangan">
                                        @error('nm_kerjasama')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Keterangan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Keterangan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="keterangan"
                                                  class="form-control @error('keterangan') is-invalid @enderror"
                                                  id="keteranganTextTambah"
                                                  placeholder="Masukan keterangan">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Syarat  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Syarat
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="syarat"
                                                  class="form-control @error('syarat') is-invalid @enderror"
                                                  id="syaratTextTambah"
                                                  placeholder="Masukan syarat">{{ old('syarat') }}</textarea>
                                        @error('syarat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Foto Kerjasama  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Foto Asuransi Kesehatan</label>
                                        <input type="file"
                                               name="foto_kerjasama"
                                               class="file-upload-default"
                                               hidden>
                                        <div class="input-group col-xs-12">
                                            <input type="text"
                                                   class="form-control file-upload-info @error('foto_kerjasama') is-invalid @enderror"
                                                   disabled
                                                   placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-success"
                                                        type="button">Upload</button>
                                            </span>
                                            @error('foto_kerjasama')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
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
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
@push('custom-script')
    <script>
        CKEDITOR.replace('keteranganTextTambah');
        CKEDITOR.replace('syaratTextTambah');
    </script>
@endpush
