@extends('landing.layout.master')
@section('title', 'Kerjasama Asuransi | RSKM Regina Eye Center')
@section('menuInformasiPasien', 'active')
@section('menuKerjasamaAsuransi', 'active')

@section('content')
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Kerjasama Asuransi</h1>
            <p class="lead">Kami bekerja sama dengan berbagai penyedia asuransi untuk memudahkan Anda mendapatkan layanan kesehatan mata terbaik.</p>
        </div>
    </section>

    @include('landing.main.kerjasama')
@endsection
