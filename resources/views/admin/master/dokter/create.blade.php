@extends('dashboard.layout.master')
@section('title', 'Tambah Data Dokter | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuDataDokter', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Data Dokter</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-dokter.index') }}"
                           class="text-primary">Data Dokter</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Data Dokter</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-dokter.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Pengguna  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Pengguna
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror"
                                                id="selectedUsers"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Pengguna</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id ?? '' }}"
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Spesialis  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Spesialis
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="spesialis_id"
                                                class="form-control @error('spesialis_id') is-invalid @enderror"
                                                id="selectedSpesialis"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Spesialis</option>
                                            @foreach ($spesialis as $data)
                                                <option value="{{ $data->id ?? '' }}"
                                                        {{ old('spesialis_id') == $data->id ? 'selected' : '' }}>{{ $data->nama_spesialis ?? '-' }}</option>
                                            @endforeach
                                        </select>
                                        @error('spesialis_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Nama Dokter  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Nama Dokter
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="nm_dokter"
                                               class="form-control @error('nm_dokter') is-invalid @enderror"
                                               value="{{ old('nm_dokter') }}"
                                               placeholder="Masukan nama dokter">
                                        @error('nm_dokter')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Tempat Lahir  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Tempat Lahir
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="tmp_lahir"
                                               class="form-control @error('tmp_lahir') is-invalid @enderror"
                                               value="{{ old('tmp_lahir') }}"
                                               placeholder="Masukan tempat lahir">
                                        @error('tmp_lahir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Tanggal Lahir  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Tanggal Lahir
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="tgl_lahir"
                                               class="form-control @error('tgl_lahir') is-invalid @enderror"
                                               value="{{ old('tgl_lahir', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                               placeholder="Masukan tanggal lahir"
                                               id="tanggalLahir">
                                        @error('tgl_lahir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jenis Kelamin  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Jenis Kelamin
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="jk"
                                                class="form-control @error('jk') is-invalid @enderror"
                                                id="selectedJenisKelamin"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>Pilih Jenis Kelamin</option>
                                            <option value="1"
                                                    {{ old('jk') == '1' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="2"
                                                    {{ old('jk') == '2' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jk')
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
                                               name="telp_dokter"
                                               class="form-control @error('telp_dokter') is-invalid @enderror"
                                               value="{{ old('telp_dokter') }}"
                                               placeholder="Masukan nomor telepon">
                                        @error('telp_dokter')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Slug  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Slug
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="slug"
                                               class="form-control @error('slug') is-invalid @enderror"
                                               value="{{ old('slug') }}"
                                               placeholder="Masukan slug dokter">
                                        @error('slug')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Pendidikan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Pendidikan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="pendidikan"
                                                  class="form-control @error('pendidikan') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('pendidikan') }}</textarea>
                                        @error('pendidikan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Alamat  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Alamat
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="alamat"
                                                  class="form-control @error('alamat') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Tentang  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Tentang Dokter
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="tentang"
                                                  class="form-control @error('tentang') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('tentang') }}</textarea>
                                        @error('tentang')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Keahlian  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Keahlian
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="keahlian"
                                                  class="form-control @error('keahlian') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('keahlian') }}</textarea>
                                        @error('keahlian')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Foto Dokter  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Foto Dokter</label>
                                        <input type="file"
                                               name="foto_dokter"
                                               class="file-upload-default"
                                               hidden>
                                        <div class="input-group col-xs-12">
                                            <input type="text"
                                                   class="form-control file-upload-info @error('foto_dokter') is-invalid @enderror"
                                                   disabled
                                                   placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-success"
                                                        type="button">Upload</button>
                                            </span>
                                            @error('foto_dokter')
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
                            <a href="{{ route('admin-dokter.index') }}"
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
            // Datepicker
            $("#tanggalLahir").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto",
                language: "id"
            }).on('show', function() {
                $('.datepicker').hide().fadeIn(200);
            });
            $('#selectedUsers').select2({
                theme: 'bootstrap4',
            });
            $('#selectedSpesialis').select2({
                theme: 'bootstrap4',
            });
            $('#selectedJenisKelamin').select2({
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush
