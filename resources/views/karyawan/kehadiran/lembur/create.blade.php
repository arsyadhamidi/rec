@extends('dashboard.layout.master')
@section('title', 'Tambah Data Lembur | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataLembur', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Tambah Data Lembur</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('karyawan-lembur.index') }}"
                           class="text-primary">Data Lembur</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Tambah Data Lembur</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('karyawan-lembur.store') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Atasan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Atasan <span class="text-danger">*</span> </label>
                                        <select name="atasan_id"
                                                class="form-control @error('atasan_id') is-invalid @enderror"
                                                id="selectedAtasan"
                                                style="width: 100%">
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

                                {{--  Tanggal Mulai  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Tanggal Mulai
                                            <span class="text-danger">*</span>
                                        </label>
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
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Tanggal Selesai
                                            <span class="text-danger">*</span>
                                        </label>
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

                                {{--  Jam Mulai  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Jam Mulai
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="jam_mulai"
                                               class="form-control @error('jam_mulai') is-invalid @enderror"
                                               value="{{ old('jam_mulai', \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               id="jamMulai">
                                        @error('jam_mulai')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jam Selesai  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Jam Selesai
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="jam_selesai"
                                               class="form-control @error('jam_selesai') is-invalid @enderror"
                                               value="{{ old('jam_selesai', \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               id="jamSelesai">
                                        @error('jam_selesai')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Alasan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Alasan Lembur
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="alasan"
                                                  class="form-control @error('alasan') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan alasan lembur">{{ old('alasan') }}</textarea>
                                        @error('alasan')
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
                            <a href="{{ route('karyawan-lembur.index') }}"
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

            {{--  Jam Selesai  --}}
            $("#jamSelesai").timepicker({
                showMeridian: false,
                defaultTime: false,
                minuteStep: 1,
                icons: {
                    up: 'bi bi-chevron-up',
                    down: 'bi bi-chevron-down'
                }
            });

            {{--  Jam Mulai  --}}
            $("#jamMulai").timepicker({
                showMeridian: false,
                defaultTime: false,
                minuteStep: 1,
                icons: {
                    up: 'bi bi-chevron-up',
                    down: 'bi bi-chevron-down'
                }
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

            {{--  Atasan  --}}
            $('#selectedAtasan').select2({
                theme: 'bootstrap4',
                placeholder: '== Pilih Atasan ==',
                ajax: {
                    url: '{{ route('karyawan-lembur.getUser') }}',
                    dataType: 'json',
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
                                    id: item.id,
                                    text: `${item.name}`
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

        });
    </script>
@endpush
