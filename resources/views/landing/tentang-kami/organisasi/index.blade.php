@extends('landing.layout.master')
@section('title', 'Struktur Organisasi | RSKM Regina Eye Center')
@section('menuTentangKami', 'active')
@section('menuStrukturOrganisasi', 'active')

@section('content')

    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 50vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Struktur Organisasi</h1>
            <p class="lead">Rumah Sakit Khusus Mata Regina Eye Center</p>
        </div>
    </section>

    <!-- STRUKTUR ORGANISASI SECTION -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold text-orange mb-4">Struktur Organisasi</h2>
            <p class="text-muted mb-5">Struktur organisasi Rumah Sakit Khusus Mata Regina Eye Center menggambarkan tata kelola dan koordinasi setiap bagian dalam memberikan pelayanan kesehatan mata
                terbaik.</p>

            <div class="image-wrapper shadow-sm p-3 bg-white rounded-4">
                <img src="{{ asset('images/organisasi.jpg') }}"
                     alt="Struktur Organisasi Regina Eye Center"
                     class="img-fluid rounded-4">
            </div>
        </div>
    </section>
@endsection
