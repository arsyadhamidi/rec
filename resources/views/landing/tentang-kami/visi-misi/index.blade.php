@extends('landing.layout.master')
@section('title', 'Visi & Misi | RSKM Regina Eye Center')
@section('menuTentangKami', 'active')
@section('menuVisiMisi', 'active')

@section('content')
    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 50vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Visi & Misi</h1>
            <p class="lead">Rumah Sakit Khusus Mata Regina Eye Center</p>
        </div>
    </section>


    <!-- VISI & MISI SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 text-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold text-orange mb-3">Visi</h2>
                    <p class="lead text-muted fst-italic">
                        “Menjadi Rumah Sakit Khusus Mata yang unggul dengan pelayanan prima.”
                    </p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold text-orange mb-4 text-center">Misi</h2>
                    <div class="mission-list bg-white p-4 rounded-4 shadow-sm">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start">
                                <div class="icon me-3">
                                    <i class="bi bi-eye text-orange fs-4"></i>
                                </div>
                                <p class="mb-0">Memberikan pelayanan mata secara prima.</p>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <div class="icon me-3">
                                    <i class="bi bi-people text-orange fs-4"></i>
                                </div>
                                <p class="mb-0">Meningkatkan kualitas sumber daya manusia (SDM) yang profesional.</p>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="icon me-3">
                                    <i class="bi bi-hospital text-orange fs-4"></i>
                                </div>
                                <p class="mb-0">Meningkatkan sarana dan prasarana yang mengutamakan kualitas pelayanan mata.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
