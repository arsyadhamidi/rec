@extends('dashboard.layout.master')
@section('title', 'Edit Data Lembur | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataLembur', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Data Lembur</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Data Lembur</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('admin-lembur.update', $lemburs->lembur_id ?? '') }}"
                      method="POST">
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
                                               value="{{ old('tgl_mulai', $lemburs->tgl_mulai ? \Carbon\Carbon::parse($lemburs->tgl_mulai)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
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
                                               value="{{ old('tgl_selesai', $lemburs->tgl_selesai ? \Carbon\Carbon::parse($lemburs->tgl_selesai)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
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
                                               value="{{ old('jam_mulai', $lemburs->jam_mulai ? \Carbon\Carbon::parse($lemburs->jam_mulai)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
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
                                               value="{{ old('jam_selesai', $lemburs->jam_selesai ? \Carbon\Carbon::parse($lemburs->jam_selesai)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
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
                                                    {{ old('status', $lemburs->status) == '4' ? 'selected' : '' }}>Disetujui Direktur</option>
                                            <option value="3"
                                                    {{ old('status', $lemburs->status) == '3' ? 'selected' : '' }}>Disetujui Oleh SDM</option>
                                            <option value="2"
                                                    {{ old('status', $lemburs->status) == '2' ? 'selected' : '' }}>Disetujui Oleh Atasan</option>
                                            <option value="1"
                                                    {{ old('status', $lemburs->status) == '1' ? 'selected' : '' }}>Proses Pengajuan</option>
                                            <option value="0"
                                                    {{ old('status', $lemburs->status) == '0' ? 'selected' : '' }}>Ditolak</option>
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
                                        <label>Alasan Lembur
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="alasan"
                                                  class="form-control @error('alasan') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan alasan lembur">{{ old('alasan', $lemburs->alasan ?? '-') }}</textarea>
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
                            <a href="{{ route('admin-lembur.index') }}"
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
                    url: '{{ route('admin-lembur.getUser') }}',
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

            var atasanId = "{{ $lemburs->atasan_id ?? '' }}";
            var atasanText = "{{ $lemburs->nama_atasan ?? '-' }}";
            if (atasanId) {
                var newOption = new Option(atasanText, atasanId, true, true);
                $("#selectedAtasan").append(newOption).trigger("change");
            }

            {{--  Karyawan  --}}
            $('#selectedUser').select2({
                theme: 'bootstrap4',
                placeholder: '== Pilih Karyawan ==',
                ajax: {
                    url: '{{ route('admin-lembur.getUser') }}',
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

            var userId = "{{ $lemburs->user_id ?? '' }}";
            var userText = "{{ $lemburs->nama_karyawan ?? '-' }}";
            if (userId) {
                var newOption = new Option(userText, userId, true, true);
                $("#selectedUser").append(newOption).trigger("change");
            }

        });
    </script>
@endpush
