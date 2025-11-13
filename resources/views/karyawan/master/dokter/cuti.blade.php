@extends('dashboard.layout.master')
@section('title', 'Cuti Dokter | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuDataDokter', 'active')

@section('content')
    <div class="row">
        <div class="col-lg">
            <h3>Cuti Dokter</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Cuti Dokter</li>
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
                                data-bs-target="#cutiDokterModalTambah">
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
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cutis as $cuti)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $cuti->nm_dokter ?? '-' }}</td>
                                            <td>{{ $cuti->tgl_mulai ?? '-' }}</td>
                                            <td>{{ $cuti->tgl_selesai ?? '-' }}</td>
                                            <td class="d-flex">
                                                <button type="button"
                                                        class="btn btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cutiDokterModalEdit{{ $cuti->id ?? '-' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade"
                                                     id="cutiDokterModalEdit{{ $cuti->id ?? '' }}"
                                                     aria-labelledby="exampleModalLabel"
                                                     aria-hidden="true">
                                                    <form action="{{ route('customer-dokter.updatecutidokter', $cuti->id ?? '') }}"
                                                          method="POST">
                                                        @csrf
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title fs-5">Edit Cuti Dokter</h5>
                                                                    <button type="button"
                                                                            class="btn-light"
                                                                            data-bs-dismiss="modal">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="mb-3">
                                                                                <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                                                                <input type="text"
                                                                                       name="tgl_mulai"
                                                                                       class="form-control datepicker @error('tgl_mulai') is-invalid @enderror"
                                                                                       value="{{ old('tgl_mulai', $cuti->tgl_mulai ? \Carbon\Carbon::parse($cuti->tgl_mulai)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                                                       id="tglMulaiEdit{{ $cuti->id ?? '' }}">
                                                                                @error('tgl_mulai')
                                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <div class="mb-3">
                                                                                <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                                                                <input type="text"
                                                                                       name="tgl_selesai"
                                                                                       class="form-control datepicker @error('tgl_selesai') is-invalid @enderror"
                                                                                       value="{{ old('tgl_selesai', $cuti->tgl_selesai ? \Carbon\Carbon::parse($cuti->tgl_selesai)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                                                                       id="tglSelesaiEdit{{ $cuti->id ?? '' }}">
                                                                                @error('tgl_selesai')
                                                                                    <div class="invalid-feedback">{{ $message }}</div>
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

                                                <form action="{{ route('customer-dokter.destroycutidokter', $cuti->id ?? '') }}"
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
         id="cutiDokterModalTambah"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <form action="{{ route('customer-dokter.storecutidokter') }}"
              method="POST">
            @csrf
            <input type="hidden"
                   class="form-control"
                   name="dokter_id"
                   value="{{ $dokters->id ?? '-' }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5">Cuti Dokter</h5>
                        <button type="button"
                                class="btn-light"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Tanggal Mulai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="tgl_mulai"
                                           class="form-control @error('tgl_mulai') is-invalid @enderror"
                                           value="{{ old('tgl_mulai', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                           id="tglMulaiTambah">
                                    @error('tgl_mulai')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Tanggal Selesai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="tgl_selesai"
                                           class="form-control @error('tgl_selesai') is-invalid @enderror"
                                           value="{{ old('tgl_selesai', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                           id="tglSelesaiTambah">
                                    @error('tgl_selesai')
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
        $("#tglMulaiTambah").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });

        $("#tglSelesaiTambah").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            language: "id"
        }).on('show', function() {
            $('.datepicker').hide().fadeIn(200);
        });
    </script>
    <script>
        $(document).ready(function() {
            // Saat modal ditampilkan
            $(document).on('shown.bs.modal', '.modal', function() {
                const modal = $(this);
                const modalId = modal.attr('id');
                const cutiId = modalId.replace('cutiDokterModalEdit', '');

                // Inisialisasi Datepicker untuk Tanggal Mulai & Selesai
                $('#tglMulaiEdit' + cutiId + ', #tglSelesaiEdit' + cutiId).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true
                });
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
