@extends('landing.layout.master')
@section('title', 'Profil Singkat | RSKM Regina Eye Center')
@section('menuTentangKami', 'active')
@section('menuProfilSingkat', 'active')

@section('content')

<!-- HERO SECTION -->
<section class="hero-section text-center text-white d-flex align-items-center"
    style="background: linear-gradient(to right, #f58220, #f4a261); height: 50vh;">
    <div class="container">
        <h1 class="fw-bold display-5">Profil Singkat</h1>
        <p class="lead">Rumah Sakit Khusus Mata Regina Eye Center</p>
    </div>
</section>

<!-- TENTANG RUMAH SAKIT -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('images/about.jpg') }}" alt="RSKM Regina Eye Center" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold text-uppercase mb-3 text-primary">Tentang Regina Eye Center</h3>
                <p class="text-justify">
                    <strong>Rumah Sakit Khusus Mata Regina Eye Center</strong> adalah rumah sakit swasta
                    yang berlokasi di <strong>Jl. H. Agus Salim No. 11 A, Kelurahan Sawahan, Kecamatan Padang Timur, Kota Padang,
                    Provinsi Sumatera Barat</strong>.
                </p>
                <p class="text-justify">
                    Regina Eye Center didirikan pada tanggal <strong>28 April 2008</strong> di bawah kepemilikan
                    <strong>PT. Regina Cahaya Insani</strong> berdasarkan Akta Notaris Nomor 28 tanggal 25 Juni 2008.
                    Awalnya berdiri sebagai Klinik Mata Regina Eye Center, dan seiring perkembangan pelayanan serta
                    peningkatan mutu, klinik ini berubah status menjadi <strong>Rumah Sakit Khusus Mata Regina Eye Center</strong>
                    sesuai dengan SK Dinas Penanaman Modal dan Pelayanan Terpadu Kota Padang
                    Nomor: 001.1/PK-10RS/DPMPTSP/VIII-202.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- PEMILIK -->
<section class="py-5">
    <div class="container text-center">
        <h3 class="fw-bold text-uppercase mb-4 text-primary">Pemilik Rumah Sakit</h3>
        <div class="row justify-content-center gy-4">
            <!-- Owner 1 -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('images/dokter/DPJP-01.png') }}" class="card-img-top rounded-top" alt="Owner">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1">Dr. dr. Muhammad Hidayat, SpM(K)</h6>
                        <p class="small text-muted">Dokter Spesialis Mata<br>Neuro & Refractive Surgery</p>
                    </div>
                </div>
            </div>
            <!-- Owner 2 -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('images/dokter/DPJP-02.png') }}" class="card-img-top rounded-top" alt="Owner">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1">dr. Harmen, SpM(K)</h6>
                        <p class="small text-muted">Dokter Spesialis Mata<br>Glaukoma & Cataract Surgery</p>
                    </div>
                </div>
            </div>
            <!-- Owner 3 -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('images/dokter/DPJP-03.png') }}" class="card-img-top rounded-top" alt="Owner">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1">dr. Zukhri Zainun, SpM</h6>
                        <p class="small text-muted">Dokter Spesialis Mata<br>Cataract & General Ophthalmology</p>
                    </div>
                </div>
            </div>
            <!-- Owner 4 -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('images/dokter/anwar.jpg') }}" class="card-img-top rounded-top" alt="Owner">
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-1">Anwar Yusuf, SH</h6>
                        <p class="small text-muted">Direktur PT. Regina Cahaya Insani</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PENGAWAS -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="fw-bold text-uppercase mb-4 text-primary">Pengawas Rumah Sakit</h3>
        <div class="col-md-6 mx-auto">
            <div class="card border-0 shadow-sm">
                <img src="{{ asset('images/dokter/DPJP-04.png') }}" class="card-img-top rounded-top" alt="Pengawas">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-1">dr. Irayanti, SpM(K), MARS</h6>
                    <p class="small text-muted mb-2">Dokter Spesialis Mata</p>
                    <p class="small text-muted">
                        • Direktur RSUP M. Djamil (2010–2015)<br>
                        • Direktur Utama PNM Cicendo (2015–2023)<br>
                        • Pengawas RSKM Regina Eye Center (2023–sekarang)
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DIREKTUR -->
<section class="py-5">
    <div class="container text-center">
        <h3 class="fw-bold text-uppercase mb-4 text-primary">Direktur Rumah Sakit</h3>
        <div class="col-md-6 mx-auto">
            <div class="card border-0 shadow-sm">
                <img src="{{ asset('images/dokter/DPJP-10.png') }}" class="card-img-top rounded-top" alt="Direktur">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-1">dr. Silvy Fetriyanita, Sp.M.</h6>
                    <p class="small text-muted mb-2">S-1 Fakultas Kedokteran UNAND</p>
                    <p class="small text-muted">
                        • Direktur RSKM REGINA EYE CENTER (2025–sekarang)
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
