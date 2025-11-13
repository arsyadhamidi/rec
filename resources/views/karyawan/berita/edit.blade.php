@extends('dashboard.layout.master')
@section('title', 'Edit Publikasi / Berita | RSKM Regina Eye Center')
@section('menuBerita', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data Publikasi / Berita<h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb"
                            style="margin-left: -15px">
                            <li class="breadcrumb-item"><a href="/dashboard"
                                   class="text-primary">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('humas-berita.index') }}"
                                   class="text-primary">Data Publikasi / Berita</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">Edit Data Publikasi / Berita
                            <li>
                        </ol>
                    </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('humas-berita.update', $beritas->id ?? '') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            {{--  Tanggal Berita  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Tanggal Berita <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text"
                                           name="tgl_berita"
                                           class="form-control @error('tgl_berita') is-invalid @enderror"
                                           value="{{ old('tgl_berita', $beritas->tgl_berita ? \Carbon\Carbon::parse($beritas->tgl_berita)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                           placeholder="Masukan tanggal berita"
                                           id="tanggalBerita">
                                    @error('tgl_berita')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            {{--  Judul Berita  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Judul Berita <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="judul"
                                              class="form-control @error('judul') is-invalid @enderror"
                                              rows="5"
                                              placeholder="Masukan keterangan">{{ old('judul', $beritas->judul ?? '-') }}</textarea>
                                    @error('judul')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            {{--  Slug  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Slug Berita <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text"
                                           name="slug"
                                           class="form-control @error('slug') is-invalid @enderror"
                                           value="{{ old('slug', $beritas->slug ?? '-') }}"
                                           placeholder="Masukan slug berita">
                                    @error('slug')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            {{--  Ringkasan Berita  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Ringkasan Berita <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="ringkasan"
                                              class="form-control @error('ringkasan') is-invalid @enderror"
                                              rows="5"
                                              placeholder="Masukan keterangan">{{ old('ringkasan', $beritas->ringkasan ?? '-') }}</textarea>
                                    @error('ringkasan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            {{--  Isi Berita  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Isi Berita <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <textarea name="isi_berita"
                                              class="form-control @error('isi_berita') is-invalid @enderror"
                                              rows="5"
                                              placeholder="Masukan keterangan"
                                              id="editorIsiBerita">{{ old('isi_berita', $beritas->isi_berita ?? '-') }}</textarea>
                                    @error('isi_berita')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            {{--  Status  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Status Publikasi<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="status"
                                            class="form-control @error('status') is-invalid @enderror"
                                            id="selectedStatus"
                                            style="width: 100%">
                                        <option value=""
                                                selected>== Pilih Status ==</option>
                                        <option value="1"
                                                {{ old('status', $beritas->status) == '1' ? 'selected' : '' }}>Publish</option>
                                        <option value="0"
                                                {{ old('status', $beritas->status) == '0' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{--  Kategori Berita  --}}
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Kategori Berita<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="kategori_berita_id"
                                            class="form-control @error('kategori_berita_id') is-invalid @enderror"
                                            id="selectedKategori"
                                            style="width: 100%">
                                        <option value=""
                                                selected>== Pilih Kategori Berita ==</option>
                                        @foreach ($kategoris as $data)
                                            <option value="2"
                                                    {{ old('kategori_berita_id', $beritas->kategori_berita_id) == $data->id ? 'selected' : '' }}>{{ $data->nm_kategori ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_berita_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{--  Gambar  --}}
                            <div class="mb-3 row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Gambar
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                               name="gambar_berita"
                                               class="file-upload-default"
                                               hidden>
                                        <div class="input-group col-xs-12">
                                            <input type="text"
                                                   class="form-control file-upload-info @error('gambar_berita') is-invalid @enderror"
                                                   disabled
                                                   placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-success"
                                                        type="button">Upload</button>
                                            </span>
                                            @error('gambar_berita')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <span class="text-muted">File di upload berupa: PNG, JPG, atau JPEG dengan kapasitas maksimal 10 MB</span>
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
                            <a href="{{ route('humas-berita.index') }}"
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
        {{--  Tanggal Berita  --}}
        $("#tanggalBerita").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });

        {{--  Status  --}}
        $('#selectedStatus').select2({
            theme: 'bootstrap4',
        });

        {{--  Kategori  --}}
        $('#selectedKategori').select2({
            theme: 'bootstrap4',
        });

        CKEDITOR.replace('editorIsiBerita');
    </script>
@endpush
