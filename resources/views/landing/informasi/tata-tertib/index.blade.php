@extends('landing.layout.master')
@section('title', 'Tata Tertib | RSKM Regina Eye Center')
@section('menuInformasiPasien', 'active')
@section('menuTataTertib', 'active')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center text-white d-flex align-items-center"
             style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
        <div class="container">
            <h1 class="fw-bold display-5">Tata Tertib Pengunjung</h1>
            <p class="lead">Peraturan demi kenyamanan, keselamatan, dan ketertiban bersama di RSKM Regina Eye Center.</p>
        </div>
    </section>

    <!-- Tata Tertib Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center fw-bold mb-4 text-uppercase" style="color: #f58220;">
                        Tata Tertib Pengunjung / Penunggu Pasien
                    </h3>
                    <h5 class="text-center mb-4">Rumah Sakit Khusus Mata Regina Eye Center</h5>

                    <ol class="fs-6 lh-lg" style="text-align: justify;">
                        <li>
                            <strong>Waktu berkunjung (besuk) pasien:</strong><br>
                            <div class="ms-3">
                                Pagi : <strong>11.00 — 14.00 WIB</strong><br>
                                Sore : <strong>17.00 — 20.00 WIB</strong>
                            </div>
                        </li>
                        <li>
                            Pintu masuk/keluar hanya dari pintu depan rumah sakit.
                        </li>
                        <li>
                            Untuk kepentingan kesehatan, <strong>anak sehat di bawah usia 12 tahun</strong> tidak diizinkan memasuki area rumah sakit.
                        </li>
                        <li>
                            Untuk keselamatan dan kenyamanan pasien:
                            <ol type="a" class="ms-3">
                                <li>Jumlah penunggu pasien rawat inap maupun yang menemani pasien rawat jalan/poliklinik <strong>tidak diperkenankan lebih dari 2 (dua) orang.</strong></li>
                                <li>Jumlah pengunjung yang masuk ke ruangan perawatan pada saat bersamaan <strong>tidak lebih dari 2 (dua) orang.</strong></li>
                                <li>Pengunjung yang sedang sakit tenggorok, flu, atau mengalami penyakit menular <strong>tidak diperbolehkan mengunjungi pasien.</strong></li>
                            </ol>
                        </li>
                        <li>
                            Tidak diperkenankan membawa barang berharga, alat/barang elektronik
                            (<em>rice cooker</em>, laptop/notebook, setrika, dan sejenisnya) ke lingkungan rumah sakit.
                            <br>
                            <strong>Pihak rumah sakit tidak bertanggung jawab atas kehilangan, pencurian, atau kerusakan terhadap barang tersebut.</strong>
                        </li>
                        <li>
                            <strong>Dilarang merokok</strong> selama berada di lingkungan rumah sakit, termasuk area parkir.
                        </li>
                        <li>
                            <strong>Dilarang merusak atau mengambil peralatan maupun fasilitas rumah sakit.</strong>
                        </li>
                        <li>
                            Setelah jam kunjungan berakhir, petugas keamanan akan menutup/mengunci area yang telah ditentukan.
                        </li>
                        <li>
                            Petugas keamanan <strong>berwenang melaksanakan penertiban</strong> sesuai ketentuan di atas.
                        </li>
                    </ol>

                    <div class="text-center mt-5">
                        <p class="fw-semibold text-secondary">
                            Terima kasih atas kerja sama dan pengertian Anda dalam menjaga kenyamanan bersama.
                        </p>
                        <hr class="w-50 mx-auto" style="border-top: 3px solid #f58220;">
                        <p class="text-muted mb-0">RSKM Regina Eye Center © {{ date('Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
