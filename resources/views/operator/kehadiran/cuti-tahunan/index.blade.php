@extends('dashboard.layout.master')
@section('title', 'Data Cuti Tahunan | RSKM Regina Eye Center')
@section('menuManajemenKehadiran', 'active')
@section('menuDataCutiTahunan', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h3>Data Cuti Tahunan</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Data Cuti Tahunan</li>
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
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card card-side-left">
                    <div class="card-body">
                        <h4 class="card-title">Pencarian Data Cuti Tahunan</h4>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <input type="text"
                                           class="form-control"
                                           id="searchByDate">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <select name="status"
                                            class="form-control"
                                            id="selectedStatus">
                                        <option value=""
                                                selected>== Pilih Status Cuti Tahunan ==</option>
                                        <option value="4">Disetujui Direktur</option>
                                        <option value="3">Disetujui Oleh SDM</option>
                                        <option value="2">Disetujui Oleh Atasan</option>
                                        <option value="1">Proses Pengajuan</option>
                                        <option value="0">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-3">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped"
                               id="myTable"
                               style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="width: 4%">No.</th>
                                    <th>Nama</th>
                                    <th>Tgl.Pengajuan</th>
                                    <th>Tgl.Mulai</th>
                                    <th>Tgl.Selesai</th>
                                    <th>Tgl.Masuk</th>
                                    <th>Lama</th>
                                    <th>Status</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
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

            {{--  Status  --}}
            $('#selectedStatus').select2({
                theme: 'bootstrap4',
            });

            var startDate = moment().startOf('year');
            var endOfDate = moment().endOf('year');

            $('#searchByDate').daterangepicker({
                startDate: startDate,
                endDate: endOfDate,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    customRangeLabel: 'Pilih Rentang Tanggal',
                    daysOfWeek: [
                        'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'
                    ],
                    monthNames: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start_date, end_date) {
                myTable.draw();
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
                    url: "{{ route('operator-cutitahunan.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();
                        var startDate = $('#searchByDate').data('daterangepicker').startDate.format(
                            'YYYY-MM-DD');
                        var endDate = $('#searchByDate').data('daterangepicker').endDate.format(
                            'YYYY-MM-DD');

                        data.start_date = startDate;
                        data.end_date = endDate;

                        data.status = $('#selectedStatus').val();

                        // Update tombol export PDF dan Excel agar ikut tanggal filter
                        $('#generatepdf').attr('href',
                            `{{ route('operator-cutitahunan.generatepdf') }}?status=${status}&start_date=${startDate}&end_date=${endDate}`
                        );

                        $('#generateexcel').attr('href',
                            `{{ route('operator-cutitahunan.generateexcel') }}?status=${status}&start_date=${startDate}&end_date=${endDate}`
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
                        data: "nama_karyawan",
                        name: "nama_karyawan",
                        defaultContent: "-",
                    },
                    {
                        data: 'tgl_pengajuan',
                        name: 'tgl_pengajuan',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            var tglPengajuan = row.tgl_pengajuan ? new Date(row.tgl_pengajuan)
                                .toLocaleDateString() : '-';

                            return tglPengajuan;
                        }
                    },
                    {
                        data: 'tgl_mulai',
                        name: 'tgl_mulai',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            var tglMulai = row.tgl_mulai ? new Date(row.tgl_mulai)
                                .toLocaleDateString() : '-';

                            return tglMulai;
                        }
                    },
                    {
                        data: 'tgl_selesai',
                        name: 'tgl_selesai',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            var tglSelesai = row.tgl_selesai ? new Date(row.tgl_selesai)
                                .toLocaleDateString() : '-';

                            return tglSelesai;
                        }
                    },
                    {
                        data: 'tgl_masuk',
                        name: 'tgl_masuk',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            var tglMasuk = row.tgl_masuk ? new Date(row.tgl_masuk)
                                .toLocaleDateString() : '-';

                            return tglMasuk;
                        }
                    },
                    {
                        data: 'lama_cuti',
                        name: 'lama_cuti',
                        defaultContent: '-',
                    },
                    {
                        data: 'status',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            var status = row.status;
                            if (status == '4') {
                                return '<span class="badge badge-success">Disetujui Direktur</span>';
                            } else if (status == '1') {
                                return '<span class="badge badge-warning">Proses Pengajuan</span>';
                            } else if (status == '2') {
                                return '<span class="badge badge-warning">Disetujui Oleh Atasan</span>';
                            } else if (status == '3') {
                                return '<span class="badge badge-primary">Disetujui Oleh SDM</span>';
                            } else if (status == '0') {
                                return '<span class="badge badge-danger">Ditolak</span>';
                            } else {
                                return '<span class="badge badge-secondary">Tidak Diketahui</span>';
                            }
                        }
                    },
                    {
                        data: "tahun",
                        name: "tahun",
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

            $('#selectedStatus').on('change', function() {
                myTable.ajax.reload();
            });

            $("#myTable").on("click", ".btn-diterima", function() {
                const resultid = $(this).data("diterima");

                if (!confirm("Apakah Anda yakin ingin data ini sudah benar?")) {
                    return;
                }

                $.ajax({
                    url: "/operator-cutitahunan/diterima/" + resultid, // route('admin-users.destroy', id)
                    type: "POST", // Laravel destroy biasanya pakai method DELETE
                    data: {
                        _token: "{{ csrf_token() }}", // wajib untuk keamanan Laravel
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            toastr.success(res.message || "Data berhasil diterima!");
                            myTable.ajax.reload(null, false); // reload tabel tanpa reset pagination
                        } else {
                            toastr.warning(res.message || "Gagal memproses data.");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Terjadi kesalahan: " + xhr.responseText);
                    },
                });
            });


            $("#myTable").on("click", ".btn-ditolak", function() {
                const resultid = $(this).data("ditolak");

                if (!confirm("Apakah Anda yakin ingin menolak data ini ?")) {
                    return;
                }

                $.ajax({
                    url: "/operator-cutitahunan/ditolak/" + resultid, // route('admin-users.destroy', id)
                    type: "POST", // Laravel destroy biasanya pakai method DELETE
                    data: {
                        _token: "{{ csrf_token() }}", // wajib untuk keamanan Laravel
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            toastr.success(res.message || "Data berhasil ditolak!");
                            myTable.ajax.reload(null, false); // reload tabel tanpa reset pagination
                        } else {
                            toastr.warning(res.message || "Gagal memproses data.");
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
