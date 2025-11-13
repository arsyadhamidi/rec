@extends('dashboard.layout.master')
@section('title', 'Edit Kegiatan Harian | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataKegiatanHarian', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3>Edit Kegiatan Harian</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Edit Kegiatan Harian</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <form action="{{ route('karyawan-kegiatanharian.update', $kegiatans->kegiatan_id ?? '') }}"
                      method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

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
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Tanggal Kegiatan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="tgl_kegiatan"
                                               class="form-control @error('tgl_kegiatan') is-invalid @enderror"
                                               value="{{ old('tgl_kegiatan', $kegiatans->tgl_kegiatan ? \Carbon\Carbon::parse($kegiatans->tgl_kegiatan)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                               id="tglKegiatan">
                                        @error('tgl_kegiatan')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  Keterangan  --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label>Keterangan Kegiatan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="keterangan"
                                                  class="form-control @error('keterangan') is-invalid @enderror"
                                                  rows="5"
                                                  placeholder="Masukan keterangan">{{ old('keterangan', $kegiatans->keterangan ?? '-') }}</textarea>
                                        @error('keterangan')
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
                            <a href="{{ route('karyawan-kegiatanharian.index') }}"
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

            {{--  Tanggal Kegiatan  --}}
            $("#tglKegiatan").datepicker({
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
                    url: '{{ route('karyawan-kegiatanharian.getUser') }}',
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

            var atasanId = "{{ $kegiatans->atasan_id ?? '' }}";
            var atasanText = "{{ $kegiatans->nama_atasan ?? '-' }}";
            if (atasanId) {
                var newOption = new Option(atasanText, atasanId, true, true);
                $("#selectedAtasan").append(newOption).trigger("change");
            }
        });
    </script>
@endpush
