@extends('dashboard.layout.master')
@section('title', 'Edit Data Cuti Tahunan | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataCutiTahunan', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data Cuti Tahunan</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('karyawan-cutitahunan.index') }}"
                           class="text-primary">Data Cuti Tahunan</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Data Cuti Tahunan</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('karyawan-cutitahunan.update', $cutis->cuti_id ?? '') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body px-5 py-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
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

                                        {{--  PJ  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="pj_id"
                                                        class="form-control @error('pj_id') is-invalid @enderror"
                                                        id="selectedPj"
                                                        style="width: 100%"
                                                        data-old-pj-id="{{ old('pj_id') }}">
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
                                                       value="{{ old('tgl_mulai', $cutis->tgl_mulai ? \Carbon\Carbon::parse($cutis->tgl_mulai)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
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
                                                       value="{{ old('tgl_selesai', $cutis->tgl_selesai ? \Carbon\Carbon::parse($cutis->tgl_selesai)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                       id="tglSelesai">
                                                @error('tgl_selesai')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{--  Tanggal Masuk  --}}
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                       name="tgl_masuk"
                                                       class="form-control @error('tgl_masuk') is-invalid @enderror"
                                                       value="{{ old('tgl_masuk', $cutis->tgl_masuk ? \Carbon\Carbon::parse($cutis->tgl_masuk)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                       id="tglMasuk">
                                                @error('tgl_masuk')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
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
                            <a href="{{ route('karyawan-cutitahunan.index') }}"
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

        {{--  ============================= Penanggung Jawab ==================================  --}}
        let $pjSelect = $("#selectedPj");

        // Ambil data awal dari Blade
        const pjId = "{{ $cutis->pj_id ?? '' }}";
        const pjText = "{{ $cutis->nama_pj ?? '' }}";

        // Inisialisasi Select2
        $pjSelect.select2({
            theme: "bootstrap4",
            placeholder: "== Pilih Penanggung Jawab ==",
            ajax: {
                url: "{{ route('karyawan-cutitahunan.getUser') }}",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.id, // ✅ harus konsisten dengan backend
                                text: item.name,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Jika sedang edit, tambahkan data awal
        if (pjId) {
            let option = new Option(pjText, pjId, true, true);
            $pjSelect.append(option).trigger('change');
        }

        {{--  ============================= Atasan ==================================  --}}
        let $atasanSelect = $("#selectedAtasan");

        // Ambil data awal dari Blade
        const atasanId = "{{ $cutis->atasan_id ?? '' }}";
        const atasanText = "{{ $cutis->nama_atasan ?? '' }}";

        // Inisialisasi Select2
        $atasanSelect.select2({
            theme: "bootstrap4",
            placeholder: "== Pilih Atasan ==",
            ajax: {
                url: "{{ route('karyawan-cutitahunan.getUser') }}",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.id, // ✅ harus konsisten dengan backend
                                text: item.name,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Jika sedang edit, tambahkan data awal
        if (atasanId) {
            let option = new Option(atasanText, atasanId, true, true);
            $atasanSelect.append(option).trigger('change');
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
