@extends('dashboard.layout.master')
@section('title', 'Edit Data Izin Keluar | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataIzinKeluar', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data Izin Keluar</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('karyawan-izinkeluar.index') }}"
                           class="text-primary">Data Izin Keluar</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Data Izin Keluar</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('karyawan-izinkeluar.update', $izins->izin_id ?? '') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Atasan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Atasan <span class="text-danger">*</span></label>
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

                                {{--  Tanggal Izin  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Tanggal
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="tgl_izin"
                                               class="form-control @error('tgl_izin') is-invalid @enderror"
                                               value="{{ old('tgl_izin', $izins->tgl_izin ? \Carbon\Carbon::parse($izins->tgl_izin)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                               id="tglIzin">
                                        @error('tgl_izin')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jam Keluar  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Jam Keluar
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="jam_keluar"
                                               class="form-control @error('jam_keluar') is-invalid @enderror"
                                               value="{{ old('jam_keluar', $izins->jam_keluar ? \Carbon\Carbon::parse($izins->jam_keluar)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               id="jamKeluar">
                                        @error('jam_keluar')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jam Kembali  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Jam Kembali
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="jam_kembali"
                                               class="form-control @error('jam_kembali') is-invalid @enderror"
                                               value="{{ old('jam_kembali', $izins->jam_kembali ? \Carbon\Carbon::parse($izins->jam_kembali)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               id="jamKembali">
                                        @error('jam_kembali')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Keperluan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Keperluan Keluar
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="keperluan"
                                                  class="form-control @error('keperluan') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keperluan keluar">{{ old('keperluan', $izins->keperluan ?? '-') }}</textarea>
                                        @error('keperluan')
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
                            <a href="{{ route('karyawan-izinkeluar.index') }}"
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

            {{--  Jam Kembali  --}}
            $("#jamKembali").timepicker({
                showMeridian: false,
                defaultTime: false,
                minuteStep: 1,
                icons: {
                    up: 'bi bi-chevron-up',
                    down: 'bi bi-chevron-down'
                }
            });

            {{--  Jam Keluar  --}}
            $("#jamKeluar").timepicker({
                showMeridian: false,
                defaultTime: false,
                minuteStep: 1,
                icons: {
                    up: 'bi bi-chevron-up',
                    down: 'bi bi-chevron-down'
                }
            });

            {{--  Tanggal Izin  --}}
            $("#tglIzin").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto",
                language: "id"
            }).on('show', function() {
                $('.datepicker').hide().fadeIn(200);
            });

            {{--  ============================= Atasan ==================================  --}}
            let $atasanSelect = $("#selectedAtasan");

            // Ambil data awal dari Blade
            const atasanId = "{{ $izins->atasan_id ?? '' }}";
            const atasanText = "{{ $izins->nama_atasan ?? '' }}";

            // Inisialisasi Select2
            $atasanSelect.select2({
                theme: "bootstrap4",
                placeholder: "== Pilih Atasan ==",
                ajax: {
                    url: "{{ route('karyawan-izinkeluar.getUser') }}",
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

        });
    </script>
@endpush
