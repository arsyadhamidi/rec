@extends('dashboard.layout.master')
@section('title', 'Data Dokter | RSKM Regina Eye Center')
@section('menuDataMaster', 'active')
@section('menuDataDokter', 'active')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <h3>Data Dokter</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb"
                    style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard"
                           class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active"
                        aria-current="page">Data Dokter</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="mb-4">
                <div class="card card-side-left">
                    <div class="card-body">
                        <h5 class="card-title">Pencarian Data Dokter</h5>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <select name="spesialis_id"
                                            class="form-control @error('spesialis_id') is-invalid @enderror"
                                            id="selectedSpesialis"
                                            style="width: 100%">
                                        <option value=""
                                                selected>Pilih Spesialis</option>
                                        @foreach ($spesialis as $data)
                                            <option value="{{ $data->id ?? '' }}">{{ $data->nama_spesialis ?? '-' }}</option>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped"
                                   id="myTable"
                                   style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th>Slug</th>
                                        <th>Dokter</th>
                                        <th>TTL</th>
                                        <th>JK</th>
                                        <th>Pendidikan</th>
                                        <th>Alamat</th>
                                        <th>Tentang</th>
                                        <th>Telepon</th>
                                        <th>Keahlian</th>
                                        <th>Spesialis</th>
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

            $('#selectedSpesialis').select2({
                theme: 'bootstrap4',
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
                    url: "{{ route('customer-dokter.index') }}",
                    data: function(data) {
                        data.page = Math.ceil(data.start / data.length) + 1;
                        data.search = $("#myTable_filter input").val();
                        data.spesialis_id = $("#selectedSpesialis").val();
                    },
                },
                columns: [{
                        'className': 'details-control',
                        'orderable': false,
                        'data': null,
                        'defaultContent': ''
                    },
                    {
                        data: "slug",
                        name: "slug",
                        defaultContent: "-",
                    },
                    {
                        data: "nm_dokter",
                        name: "nm_dokter",
                        defaultContent: "-",
                    },
                    {
                        data: "tmp_lahir",
                        name: "tmp_lahir",
                        defaultContent: "-",
                        render: function(data, type, row) {
                            var tmpLahir = row.tmp_lahir;
                            var tglLahir = row.tgl_lahir;
                            var ttl = tmpLahir + '/' + tglLahir;
                            return ttl;
                        }
                    },
                    {
                        data: "jk",
                        name: "jk",
                        defaultContent: "-",
                        render: function(data, type, row) {
                            var jekel = row.jk;
                            if (jekel == '1') {
                                return 'Laki-Laki';
                            } else {
                                return 'Perempuan';
                            }
                        }
                    },
                    {
                        data: "pendidikan",
                        name: "pendidikan",
                        defaultContent: "-",
                    },
                    {
                        data: "alamat",
                        name: "alamat",
                        defaultContent: "-",
                    },
                    {
                        data: "tentang",
                        name: "tentang",
                        defaultContent: "-",
                    },
                    {
                        data: "telp_dokter",
                        name: "telp_dokter",
                        defaultContent: "-",
                    },
                    {
                        data: "keahlian",
                        name: "keahlian",
                        defaultContent: "-",
                    },
                    {
                        data: "nama_spesialis",
                        name: "nama_spesialis",
                        defaultContent: "-",
                    },
                ],

                order: [
                    [1, "desc"]
                ],
            });

            $("#selectedSpesialis").on("change", function() {
                myTable.ajax.reload();
            });

            // Define table variable
            let table = $('#myTable').DataTable();

            // Handle click on details-control cells
            $('#myTable tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });

            // Handle click on "Expand All" button
            $('#btn-show-all-children').on('click', function() {
                // Enumerate all rows
                table.rows().every(function() {
                    // If row has details collapsed
                    if (!this.child.isShown()) {
                        // Open this row
                        this.child(format(this.data())).show();
                        $(this.node()).addClass('shown');
                    }
                });
            });

            // Handle click on "Collapse All" button
            $('#btn-hide-all-children').on('click', function() {
                // Enumerate all rows
                table.rows().every(function() {
                    // If row has details expanded
                    if (this.child.isShown()) {
                        // Collapse row details
                        this.child.hide();
                        $(this.node()).removeClass('shown');
                    }
                });
            });

            var buatJadwalRoute = "{{ route('customer-dokter.jadwal', ':id') }}";
            var cutiDokterRoute = "{{ route('customer-dokter.cutidokter', ':id') }}";

            function format(d) {
                var buatJadwalUrl = buatJadwalRoute.replace(':id', d.id);
                var cutiDokterUrl = cutiDokterRoute.replace(':id', d.id);

                return `
        <table class="table table-borderless align-middle" style="width:100%">
            <tr>
                <td><strong>Buat Jadwal Dokter :</strong></td>
                <td>
                    <a href="${buatJadwalUrl}" class="btn btn-info">
                        <i class="fas fa-arrow-right"></i> Buat Jadwal Dokter
                    </a>
                </td>
                <td><strong>Cuti Dokter :</strong></td>
                <td>
                    <a href="${cutiDokterUrl}" class="btn btn-info">
                        <i class="fas fa-arrow-right"></i> Cuti Dokter
                    </a>
                </td>
            </tr>
        </table>
    `;
            }


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
