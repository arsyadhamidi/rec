@extends('landing.layout.master')
@section('title', 'Kontak Kami | RSKM Regina Eye Center')
@section('menuLandingKontak', 'active')

@section('content')

    <!-- HERO SECTION -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5 mb-3">Kontak Kami</h1>
            <p class="lead">Hubungi kami untuk informasi dan layanan kesehatan mata terbaik</p>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Hubungi Kami</h2>
                <p class="text-muted">Kami siap membantu Anda mendapatkan pelayanan terbaik di RSKM Regina Eye Center.</p>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- INFO CONTACT -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Informasi Kontak</h5>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-warning text-white p-3 rounded-3 me-3">
                                    <i class="bi bi-geo-alt fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Alamat</h6>
                                    <p class="mb-0 text-muted">
                                        Jl. H. Agus Salim No. 11A, Kelurahan Sawahan,<br>
                                        Kecamatan Padang Timur, Kota Padang,<br>
                                        Sumatera Barat, Indonesia
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-warning text-white p-3 rounded-3 me-3">
                                    <i class="bi bi-telephone fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Telepon</h6>
                                    <p class="mb-0 text-muted">(0751) 810456</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-warning text-white p-3 rounded-3 me-3">
                                    <i class="bi bi-envelope fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Email</h6>
                                    <p class="mb-0 text-muted">humasmarketing.rec@gmail.com</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="bg-warning text-white p-3 rounded-3 me-3">
                                    <i class="bi bi-clock fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Jam Operasional</h6>
                                    <p class="mb-0 text-muted">
                                        Senin - Sabtu: 07.00 - 21.00 WIB<br>
                                        UGD: 24 Jam<br>
                                        Minggu: Tutup
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM CONTACT -->
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Kirim Pesan</h5>
                            <form action="#"
                                  method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama"
                                           class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text"
                                           id="nama"
                                           name="nama"
                                           class="form-control rounded-3"
                                           placeholder="Masukkan nama Anda"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="email"
                                           class="form-label fw-semibold">Email</label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="form-control rounded-3"
                                           placeholder="Masukkan email Anda"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="subjek"
                                           class="form-label fw-semibold">Subjek</label>
                                    <input type="text"
                                           id="subjek"
                                           name="subjek"
                                           class="form-control rounded-3"
                                           placeholder="Masukkan subjek pesan">
                                </div>

                                <div class="mb-4">
                                    <label for="pesan"
                                           class="form-label fw-semibold">Pesan</label>
                                    <textarea id="pesan"
                                              name="pesan"
                                              rows="4"
                                              class="form-control rounded-3"
                                              placeholder="Tulis pesan Anda di sini..."
                                              required></textarea>
                                </div>

                                <button type="submit"
                                        class="btn btn-warning text-white fw-bold px-4 rounded-3">
                                    <i class="bi bi-send me-2"></i> Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAP -->
            <div class="mt-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.272826958114!2d100.36262747568819!3d-0.9474597353511585!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b94760679795%3A0xd3d3556168992a03!2sRSKM%20Regina%20Eye%20Center!5e0!3m2!1sid!2sid!4v1761965821513!5m2!1sid!2sid"
                            width="100%"
                            height="450"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

@endsection
