@extends('dashboard.layout.master')
@section('title', 'Tambah Data Cuti Menikah | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataCutiMenikah', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Data Cuti Menikah</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Data Cuti Menikah</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Data Cuti Menikah</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-cutimenikah.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body px-5 py-5">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        {{--  Karyawan  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Karyawan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <select name="user_id"
                                                        class="form-control @error('user_id') is-invalid @enderror"
                                                        id="selectedUser"
                                                        style="width: 100%"
                                                        data-old-user-id="{{ old('user_id') }}">
                                                    <option value=""
                                                            selected>Pilih Karyawan</option>
                                                </select>
                                                @error('user_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Atasan  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Atasan <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="atasan_id"
                                                        class="form-control @error('atasan_id') is-invalid @enderror"
                                                        id="selectedAtasan"
                                                        style="width: 100%"
                                                        data-old-atasan-id="{{ old('atasan_id') }}">
                                                    <option value=""
                                                            selected>Pilih Atasan</option>
                                                </select>
                                                @error('atasan_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Penanggung Jawab  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="pj_id"
                                                        class="form-control @error('pj_id') is-invalid @enderror"
                                                        id="selectedPj"
                                                        style="width: 100%"
                                                        data-old-atasan-id="{{ old('pj_id') }}">
                                                    <option value=""
                                                            selected>Pilih Penanggung Jawab</option>
                                                </select>
                                                @error('pj_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Tanggal Mulai  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       name="tgl_mulai"
                                                       class="form-control @error('tgl_mulai') is-invalid @enderror"
                                                       value="{{ old('tgl_mulai', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                       id="tglMulai">
                                                @error('tgl_mulai')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Tanggal Selesai  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       name="tgl_selesai"
                                                       class="form-control @error('tgl_selesai') is-invalid @enderror"
                                                       value="{{ old('tgl_selesai', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                       id="tglSelesai">
                                                @error('tgl_selesai')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">

                                        {{--  Tanggal Masuk  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       name="tgl_masuk"
                                                       class="form-control @error('tgl_masuk') is-invalid @enderror"
                                                       value="{{ old('tgl_masuk', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                       id="tglMasuk">
                                                @error('tgl_masuk')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Status  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="status"
                                                        class="form-control @error('status') is-invalid @enderror"
                                                        id="selectedStatus"
                                                        style="width: 100%">
                                                    <option value=""
                                                            selected>== Pilih Status ==</option>
                                                    <option value="4"
                                                            {{ old('status') == '4' ? 'selected' : '' }}>Disetujui Direktur</option>
                                                    <option value="3"
                                                            {{ old('status') == '3' ? 'selected' : '' }}>Disetujui Oleh SDM</option>
                                                    <option value="2"
                                                            {{ old('status') == '2' ? 'selected' : '' }}>Disetujui Oleh Atasan</option>
                                                    <option value="1"
                                                            {{ old('status') == '1' ? 'selected' : '' }}>Proses Pengajuan</option>
                                                    <option value="0"
                                                            {{ old('status') == '0' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Alasan  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Keterangan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <textarea name="alasan"
                                                          class="form-control @error('alasan') is-invalid @enderror"
                                                          rows="5"
                                                          placeholder="Masukan keterangan">{{ old('alasan') }}</textarea>
                                                @error('alasan')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Bukti Melahirkan  --}}
                                        <div class="row mb-3">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label>File surat undangan / bukti pernikahan
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="file"
                                                           name="bukti_menikah"
                                                           class="file-upload-default"
                                                           hidden>
                                                    <div class="input-group col-xs-12">
                                                        <input type="text"
                                                               class="form-control file-upload-info @error('bukti_menikah') is-invalid @enderror"
                                                               disabled
                                                               placeholder="Upload Image">
                                                        <span class="input-group-append">
                                                            <button class="file-upload-browse btn btn-success"
                                                                    type="button">Upload</button>
                                                        </span>
                                                        @error('bukti_menikah')
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
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-success">
                                <i class="fas fa-save"></i>
                                Simpan Data
                            </button>
                            <a href="{{ route('admin-cutimenikah.index') }}"
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
        {{--  Status  --}}
        $('#selectedStatus').select2({
            theme: 'bootstrap4',
        });

        {{--  Tanggal Masuk  --}}
        $("#tglMasuk").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });

        {{--  Tanggal Selesai  --}}
        $("#tglSelesai").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });

        {{--  Tanggal Mulai  --}}
        $("#tglMulai").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });

        {{--  Penanggung Jawab  --}}
        let $pjSelect = $("#selectedPj");
        let oldIdPj = $pjSelect.data("old-pj-id");

        $pjSelect.select2({
            theme: "bootstrap4",
            placeholder: "== Pilih Penanggung Jawab ==",
            ajax: {
                url: "{{ route('admin-cutimenikah.getUser') }}",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        id: $pjSelect.data("pj-id") || "",
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Kalau ada nilai lama, fetch teks-nya dari server
        if (oldIdPj) {
            $.ajax({
                url: "{{ route('admin-cutimenikah.getUser') }}",
                data: {
                    id: oldIdPj
                }, // tambahkan endpoint supaya bisa ambil 1 data by id
                dataType: "json",
                success: function(data) {
                    if (data && data.item) {
                        let option = new Option(
                            data.item.name,
                            data.item.id,
                            true,
                            true
                        );
                        $pjSelect.append(option).trigger("change");
                    }
                },
            });
        }

        {{--  Atasan  --}}
        let $atasanSelect = $("#selectedAtasan");
        let oldIdAtasan = $atasanSelect.data("old-atasan-id");

        $atasanSelect.select2({
            theme: "bootstrap4",
            placeholder: "== Pilih Atasan ==",
            ajax: {
                url: "{{ route('admin-cutimenikah.getUser') }}",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        id: $atasanSelect.data("atasan-id") || "",
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Kalau ada nilai lama, fetch teks-nya dari server
        if (oldIdAtasan) {
            $.ajax({
                url: "{{ route('admin-cutimenikah.getUser') }}",
                data: {
                    id: oldIdAtasan
                }, // tambahkan endpoint supaya bisa ambil 1 data by id
                dataType: "json",
                success: function(data) {
                    if (data && data.item) {
                        let option = new Option(
                            data.item.name,
                            data.item.id,
                            true,
                            true
                        );
                        $atasanSelect.append(option).trigger("change");
                    }
                },
            });
        }

        {{--  Karyawan  --}}
        let $karyawanSelect = $("#selectedUser");
        let oldIdKaryawan = $karyawanSelect.data("old-user-id");

        $karyawanSelect.select2({
            theme: "bootstrap4",
            placeholder: "== Pilih Karyawan ==",
            ajax: {
                url: "{{ route('admin-cutimenikah.getUser') }}",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        id: $karyawanSelect.data("user-id") || "",
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Kalau ada nilai lama, fetch teks-nya dari server
        if (oldIdKaryawan) {
            $.ajax({
                url: "{{ route('admin-cutimenikah.getUser') }}",
                data: {
                    id: oldIdKaryawan
                }, // tambahkan endpoint supaya bisa ambil 1 data by id
                dataType: "json",
                success: function(data) {
                    if (data && data.item) {
                        let option = new Option(
                            data.item.name,
                            data.item.id,
                            true,
                            true
                        );
                        $karyawanSelect.append(option).trigger("change");
                    }
                },
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            @if (Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @endif
        });
    </script>
@endpush
