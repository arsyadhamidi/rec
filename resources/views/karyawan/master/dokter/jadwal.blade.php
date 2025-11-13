@extends('dashboard.layout.master')
@section('title', 'Jadwal Dokter | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuDataDokter', 'active')

@section('content')
    <div class="row">
        <div class="col-lg">
            <h3>Jadwal Dokter</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Jadwal Dokter</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('customer-dokter.index') }}"
                           class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#jadwalDokterModalTambah">
                            <i class="fas fa-plus"></i>
                            Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped"
                                   id="myTable"
                                   style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th>Dokter</th>
                                        <th>Hari</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwals as $jadwal)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $jadwal->nm_dokter ?? '-' }}</td>
                                            <td>
                                                @if ($jadwal->hari_dokter == '1')
                                                    Senin
                                                @elseif($jadwal->hari_dokter == '2')
                                                    Selasa
                                                @elseif($jadwal->hari_dokter == '3')
                                                    Rabu
                                                @elseif($jadwal->hari_dokter == '4')
                                                    Kamis
                                                @elseif($jadwal->hari_dokter == '5')
                                                    Jumat
                                                @elseif($jadwal->hari_dokter == '6')
                                                    Sabtu
                                                @elseif($jadwal->hari_dokter == '7')
                                                    Minggu
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $jadwal->jam_mulai ?? '-' }}</td>
                                            <td>{{ $jadwal->jam_selesai ?? '-' }}</td>
                                            <td class="d-flex">
                                                <button type="button"
                                                        class="btn btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#jadwalDokterModalEdit{{ $jadwal->id ?? '-' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade"
                                                     id="jadwalDokterModalEdit{{ $jadwal->id ?? '' }}"
                                                     aria-labelledby="exampleModalLabel"
                                                     aria-hidden="true">
                                                    <form action="{{ route('customer-dokter.updatejadwaldokter', $jadwal->id ?? '') }}"
                                                          method="POST">
                                                        @csrf
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title fs-5">Jadwal Dokter</h5>
                                                                    <button type="button"
                                                                            class="btn-light"
                                                                            data-bs-dismiss="modal">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        {{--  Hari  --}}
                                                                        <div class="col-lg-6">
                                                                            <div class="mb-3">
                                                                                <label>Hari
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <select name="hari_dokter"
                                                                                        class="form-control @error('hari_dokter') is-invalid @enderror"
                                                                                        id="selectedHariDokterEdit{{ $jadwal->id ?? '' }}"
                                                                                        style="width: 100%">
                                                                                    <option value=""
                                                                                            selected>Pilih Hari Dokter</option>
                                                                                    <option value="1"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '1' ? 'selected' : '' }}>Senin</option>
                                                                                    <option value="2"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '2' ? 'selected' : '' }}>Selasa</option>
                                                                                    <option value="3"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '3' ? 'selected' : '' }}>Rabu</option>
                                                                                    <option value="4"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '4' ? 'selected' : '' }}>Kamis</option>
                                                                                    <option value="5"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '5' ? 'selected' : '' }}>Jumat</option>
                                                                                    <option value="6"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '6' ? 'selected' : '' }}>Sabtu</option>
                                                                                    <option value="7"
                                                                                            {{ old('hari_dokter', $jadwal->hari_dokter) == '7' ? 'selected' : '' }}>Minggu</option>
                                                                                </select>
                                                                                @error('hari_dokter')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3">
                                                                            <div class="mb-3">
                                                                                <label>Jam Mulai
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text"
                                                                                       name="jam_mulai"
                                                                                       class="form-control @error('jam_mulai') is-invalid @enderror"
                                                                                       value="{{ old('jam_mulai', $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                                                                       id="jamMulaiEdit{{ $jadwal->id ?? '' }}">
                                                                                @error('jam_mulai')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3">
                                                                            <div class="mb-3">
                                                                                <label>Jam Selesai
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text"
                                                                                       name="jam_selesai"
                                                                                       class="form-control @error('jam_selesai') is-invalid @enderror"
                                                                                       value="{{ old('jam_selesai', $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}"
                                                                                       id="jamSelesaiEdit{{ $jadwal->id ?? '' }}">
                                                                                @error('jam_selesai')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit"
                                                                            class="btn btn-success">Simpan Data</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                {{--  =================================================  --}}

                                                <form action="{{ route('customer-dokter.destroyjadwaldokter', $jadwal->id ?? '') }}"
                                                      method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-outline-danger mx-2"
                                                            onclick="return confirm('Apakah anda yakin untuk menghapus data ini ?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade"
         id="jadwalDokterModalTambah"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <form action="{{ route('customer-dokter.storejadwaldokter') }}"
              method="POST">
            @csrf
            <input type="hidden"
                   class="form-control"
                   name="dokter_id"
                   value="{{ $dokters->id ?? '-' }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5">Jadwal Dokter</h5>
                        <button type="button"
                                class="btn-light"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{--  Hari  --}}
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Hari
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="hari_dokter"
                                            class="form-control @error('hari_dokter') is-invalid @enderror"
                                            id="selectedHariDokterTambah"
                                            style="width: 100%">
                                        <option value=""
                                                selected>Pilih Hari Dokter</option>
                                        <option value="1"
                                                {{ old('hari_dokter') == '1' ? 'selected' : '' }}>Senin</option>
                                        <option value="2"
                                                {{ old('hari_dokter') == '2' ? 'selected' : '' }}>Selasa</option>
                                        <option value="3"
                                                {{ old('hari_dokter') == '3' ? 'selected' : '' }}>Rabu</option>
                                        <option value="4"
                                                {{ old('hari_dokter') == '4' ? 'selected' : '' }}>Kamis</option>
                                        <option value="5"
                                                {{ old('hari_dokter') == '5' ? 'selected' : '' }}>Jumat</option>
                                        <option value="6"
                                                {{ old('hari_dokter') == '6' ? 'selected' : '' }}>Sabtu</option>
                                        <option value="7"
                                                {{ old('hari_dokter') == '7' ? 'selected' : '' }}>Minggu</option>
                                    </select>
                                    @error('hari_dokter')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label>Jam Mulai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="jam_mulai"
                                           class="form-control @error('jam_mulai') is-invalid @enderror"
                                           value="{{ old('jam_mulai', \Carbon\Carbon::now()->format('H:i:s')) }}"
                                           id="jamMulaiTambah">
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label>Jam Selesai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="jam_selesai"
                                           class="form-control @error('jam_selesai') is-invalid @enderror"
                                           value="{{ old('jam_selesai', \Carbon\Carbon::now()->format('H:i:s')) }}"
                                           id="jamSelesaiTambah">
                                    @error('jam_selesai')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                        <button type="submit"
                                class="btn btn-success">Simpan Data</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('custom-script')
    <script>
        $("#jamMulaiTambah").timepicker({
            showMeridian: false,
            defaultTime: false,
            minuteStep: 1,
            icons: {
                up: 'bi bi-chevron-up',
                down: 'bi bi-chevron-down'
            }
        });

        $("#jamSelesaiTambah").timepicker({
            showMeridian: false,
            defaultTime: false,
            minuteStep: 1,
            icons: {
                up: 'bi bi-chevron-up',
                down: 'bi bi-chevron-down'
            }
        });

        $('#selectedHariDokterTambah').select2({
            theme: 'bootstrap4',
        });
    </script>
    <script>
        $(document).ready(function() {
            // Jalankan saat modal muncul
            $(document).on('shown.bs.modal', '.modal', function() {
                const modal = $(this);

                // Ambil ID modal (unik untuk setiap jadwal)
                const modalId = modal.attr('id');
                const jadwalId = modalId.replace('jadwalDokterModalEdit', '');

                // Inisialisasi Select2 untuk dropdown hari
                const hariSelector = '#selectedHariDokterEdit' + jadwalId;
                if ($(hariSelector).length) {
                    $(hariSelector).select2({
                        theme: 'bootstrap4',
                        dropdownParent: modal // penting agar dropdown tampil di dalam modal
                    });
                }

                // Inisialisasi Timepicker untuk jam mulai
                const jamMulaiSelector = '#jamMulaiEdit' + jadwalId;
                if ($(jamMulaiSelector).length) {
                    $(jamMulaiSelector).timepicker({
                        showMeridian: false,
                        defaultTime: false,
                        minuteStep: 1,
                        icons: {
                            up: 'bi bi-chevron-up',
                            down: 'bi bi-chevron-down'
                        }
                    });
                }

                // Inisialisasi Timepicker untuk jam selesai
                const jamSelesaiSelector = '#jamSelesaiEdit' + jadwalId;
                if ($(jamSelesaiSelector).length) {
                    $(jamSelesaiSelector).timepicker({
                        showMeridian: false,
                        defaultTime: false,
                        minuteStep: 1,
                        icons: {
                            up: 'bi bi-chevron-up',
                            down: 'bi bi-chevron-down'
                        }
                    });
                }
            });
        });
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
