@extends('dashboard.layout.master')
@section('title', 'Edit Data Izin Pulang Cepat | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataPulangCepat', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data Izin Pulang Cepat</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Data Izin Pulang Cepat</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-pulangcepat.update', $izins->izin_id ?? '') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                {{--  Karyawan  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Karyawan <span class="text-danger">*</span></label>
                                        <select name="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror"
                                                id="selectedUser"
                                                style="width: 100%">
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
                                <div class="col-lg-6">
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
                                        <label>Jam karyawan pulang lebih awal
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="jam_pulang"
                                               class="form-control @error('jam_pulang') is-invalid @enderror"
                                               value="{{ old('jam_pulang', $izins->jam_pulang ? \Carbon\Carbon::parse($izins->jam_pulang)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               id="jamPulang">
                                        @error('jam_pulang')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Jam Selesai  --}}
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label>Jam seharusnya karyawan selesai bekerja
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="jam_selesai"
                                               class="form-control @error('jam_selesai') is-invalid @enderror"
                                               value="{{ old('jam_selesai', $izins->jam_selesai ? \Carbon\Carbon::parse($izins->jam_selesai)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                               id="jamSelesai">
                                        @error('jam_selesai')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Status  --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="status"
                                                class="form-control @error('status') is-invalid @enderror"
                                                id="selectedStatus"
                                                style="width: 100%">
                                            <option value=""
                                                    selected>== Pilih Status ==</option>
                                            <option value="4"
                                                    {{ old('status', $izins->status) == '4' ? 'selected' : '' }}>Disetujui Direktur</option>
                                            <option value="3"
                                                    {{ old('status', $izins->status) == '3' ? 'selected' : '' }}>Disetujui Oleh SDM</option>
                                            <option value="2"
                                                    {{ old('status', $izins->status) == '2' ? 'selected' : '' }}>Disetujui Oleh Atasan</option>
                                            <option value="1"
                                                    {{ old('status', $izins->status) == '1' ? 'selected' : '' }}>Proses Pengajuan</option>
                                            <option value="0"
                                                    {{ old('status', $izins->status) == '0' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Alasan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Alasan pulang cepat
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="alasan"
                                                  class="form-control @error('alasan') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan alasan pulang cepat">{{ old('alasan', $izins->alasan ?? '-') }}</textarea>
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
                            <a href="{{ route('admin-pulangcepat.index') }}"
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

            {{--  Status  --}}
            $('#selectedStatus').select2({
                theme: 'bootstrap4',
            });

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

            {{--  Jam Pulang  --}}
            $("#jamPulang").timepicker({
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
                    url: "{{ route('admin-pulangcepat.getUser') }}",
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

            {{--  ============================= Karyawan ==================================  --}}
            let $karyawanSelect = $("#selectedUser");

            // Ambil data awal dari Blade
            const karyawanId = "{{ $izins->user_id ?? '' }}";
            const karyawanText = "{{ $izins->nama_karyawan ?? '' }}";

            // Inisialisasi Select2
            $karyawanSelect.select2({
                theme: "bootstrap4",
                placeholder: "== Pilih Karyawan ==",
                ajax: {
                    url: "{{ route('admin-pulangcepat.getUser') }}",
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
            if (karyawanId) {
                let option = new Option(karyawanText, karyawanId, true, true);
                $karyawanSelect.append(option).trigger('change');
            }

        });
    </script>
@endpush
