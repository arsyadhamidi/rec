@extends('dashboard.layout.master')
@section('title', 'Data User | RSKM Regina Eye Center')
@section('menuDataAutentikasi', 'active')
@section('menuDataUserRegistrasi', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h3>Data User</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Data User</li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-6 text-right my-2">
            <a class="btn btn-success"
               id="generateexcel"
               target="_blank">
                <i class="fas fa-download"></i>
                Download Excel
            </a>
            <a class="btn btn-danger"
               id="generatepdf"
               target="_blank">
                <i class="fas fa-download"></i>
                Download PDF
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-4">
                <div class="card card-side-left">
                    <div class="card-body">
                        <h5 class="card-title">Pencarian Data User Registrasi</h5>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <select name="level_id"
                                            class="form-control @error('level_id') is-invalid @enderror"
                                            id="selectedLevel"
                                            style="width: 100%">
                                        <option value=""
                                                selected>Pilih Status Autentikasi</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id ?? '' }}">{{ $level->namalevel ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <select name="jabatan_id"
                                            class="form-control @error('jabatan_id') is-invalid @enderror"
                                            id="selectedJabatan"
                                            style="width: 100%">
                                        <option value=""
                                                selected>Pilih Jabatan</option>
                                        @foreach ($jabatans as $jabatan)
                                            <option value="{{ $jabatan->id ?? '' }}">{{ $jabatan->nm_jabatan ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('operator-users.create') }}"
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah Data
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped"
                                   id="myTable"
                                   style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $("#selectedLevel").select2({
                theme: "bootstrap4",
            });

            $("#selectedJabatan").select2({
                theme: "bootstrap4",
            });

            // Tampilkan Data
            let myTable = $("#myTable").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 250],
                    [10, 25, 50, 100, 250],
                ],
                language: {
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya",
                    },
                },
                ajax: {
                    url: "{{ route('operator-users.index') }}",
                    data: function(data) {
                        data.level_id = $("#selectedLevel").val();
                        data.jabatan_id = $("#selectedJabatan").val();
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();

                        $('#generatepdf').attr('href',
                            `{{ route('operator-users.generatepdf') }}?level_id=${data.level_id}&jabatan_id=${data.jabatan_id}`
                        );

                        $('#generateexcel').attr('href',
                            `{{ route('operator-users.generateexcel') }}?level_id=${data.level_id}&jabatan_id=${data.jabatan_id}`
                        );
                    },
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                    },
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "username",
                        name: "username",
                        defaultContent: "-",
                    },
                    {
                        data: "duplicate",
                        name: "duplicate",
                        defaultContent: "-",
                    },
                    {
                        data: "namalevel",
                        name: "namalevel",
                        defaultContent: "-",
                    },
                    {
                        data: "nm_jabatan",
                        name: "nm_jabatan",
                        defaultContent: "-",
                    },
                    {
                        data: "aksi",
                        name: "aksi",
                        orderable: false,
                        searchable: false,
                        defaultContent: "-",
                    },
                ],

                order: [
                    [1, "desc"]
                ],
            });

            // === Event Listener untuk Filter ===
            $("#selectedLevel").on("change", function() {
                myTable.ajax.reload();
            });

            $("#selectedJabatan").on("change", function() {
                myTable.ajax.reload();
            });

            // === Event Listener untuk Tombol Hapus User ===
            $("#myTable").on("click", ".btn-delete", function() {
                const resultid = $(this).data("resultid");

                if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    return;
                }

                $.ajax({
                    url: "/operator-users/destroy/" + resultid, // route('operator-users.destroy', id)
                    type: "POST", // Laravel destroy biasanya pakai method DELETE
                    data: {
                        _token: "{{ csrf_token() }}", // wajib untuk keamanan Laravel
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            toastr.success(res.message || "Data berhasil dihapus!");
                            myTable.ajax.reload(null, false); // reload tabel tanpa reset pagination
                        } else {
                            toastr.warning(res.message || "Gagal menghapus data.");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Terjadi kesalahan: " + xhr.responseText);
                    },
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
