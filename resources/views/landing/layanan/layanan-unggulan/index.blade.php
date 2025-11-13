@extends('landing.layout.master')
@section('title', 'Layanan Unggulan | RSKM Regina Eye Center')
@section('menuLayanan', 'active')
@section('menuLayananUnggulan', 'active')

@section('content')
<!-- HERO SECTION -->
<section class="hero-section text-center text-white d-flex align-items-center"
         style="background: linear-gradient(to right, #f58220, #f4a261); height: 45vh;">
    <div class="container">
        <h1 class="fw-bold display-5">Layanan Unggulan</h1>
        <p class="lead">Temukan layanan terbaik untuk kesehatan mata Anda</p>
    </div>
</section>

<!-- LAYANAN UNGGULAN SECTION -->
<section id="layanan-unggulan" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-blue">LAYANAN <span class="text-orange">UNGGULAN</span></h2>
            <p class="text-muted">Pilih salah satu layanan untuk melihat detailnya</p>
        </div>

        <!-- LIST LAYANAN -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="list-group shadow-sm">
                    <button class="list-group-item list-group-item-action fw-bold layanan-item"
                            data-layanan="phaco">
                        1. Operasi Katarak Phacoemulsifikasi
                    </button>
                    <button class="list-group-item list-group-item-action fw-bold layanan-item"
                            data-layanan="trabeku">
                        2. Operasi Mata Trabekulektomi
                    </button>
                    <button class="list-group-item list-group-item-action fw-bold layanan-item"
                            data-layanan="vitreo">
                        3. Vitreo-Retina
                    </button>
                    <button class="list-group-item list-group-item-action fw-bold layanan-item"
                            data-layanan="pediatrik">
                        4. Pediatrik Oftalmologi & Strabismus
                    </button>
                    <button class="list-group-item list-group-item-action fw-bold layanan-item"
                            data-layanan="rekonstruksi">
                        5. Rekonstruksi & Okuloplasti
                    </button>
                    <button class="list-group-item list-group-item-action fw-bold layanan-item"
                            data-layanan="imunologi">
                        6. Infeksi & Imunologi Mata
                    </button>
                </div>
            </div>
        </div>

        <!-- DETAIL LAYANAN -->
        <div id="detail-layanan" class="mt-5 text-center d-none">
            <h3 id="judul-layanan" class="fw-bold text-orange mb-3"></h3>
            <img id="gambar-layanan" src="" alt="Layanan" class="img-fluid rounded-4 shadow-sm mb-4"
                 style="max-height: 350px; object-fit: cover;">
            <h5 class="fw-bold text-blue">Kelebihan</h5>
            <ul id="kelebihan-layanan" class="list-unstyled text-muted mb-4"></ul>
            <p id="deskripsi-layanan" class="text-muted"></p>
        </div>
    </div>
</section>

@endsection

@push('custom-script')
<script>
    const layananData = {
        phaco: {
            judul: "Operasi Katarak Phacoemulsifikasi",
            gambar: "{{ asset('images/about.jpg') }}",
            kelebihan: [
                "Tanpa jahitan",
                "Proses cepat (± 15 menit)",
                "Pemulihan lebih cepat",
                "Pasien dapat langsung pulang setelah operasi"
            ],
            deskripsi: `Merupakan layanan Spesialis Mata yang menangani atau memperbaiki penglihatan pasien katarak
            dengan metode bedah modern bernama Phacoemulsifikasi. Prosedur ini menggunakan mesin canggih
            dengan gelombang ultrasonik untuk menghancurkan lensa keruh dan menggantinya dengan lensa buatan.`
        },
        trabeku: {
            judul: "Operasi Mata Trabekulektomi",
            gambar: "{{ asset('landing/assets/img/layanan/trabeku.jpg') }}",
            kelebihan: ["Menurunkan tekanan bola mata", "Mencegah kerusakan saraf optik"],
            deskripsi: `Layanan ini ditujukan untuk pasien dengan glaukoma, yaitu tekanan bola mata tinggi.
            Trabekulektomi dilakukan dengan membuat saluran baru untuk mengalirkan cairan mata agar tekanan menurun.`
        },
        vitreo: {
            judul: "Layanan Vitreo-Retina",
            gambar: "{{ asset('landing/assets/img/layanan/vitreo.jpg') }}",
            kelebihan: ["Diagnosis retina presisi tinggi", "Teknologi laser retina modern"],
            deskripsi: `Menangani gangguan retina dan vitreous dengan peralatan diagnostik terkini untuk menjaga fungsi penglihatan pasien.`
        },
        pediatrik: {
            judul: "Pediatrik Oftalmologi & Strabismus",
            gambar: "{{ asset('landing/assets/img/layanan/pediatrik.jpg') }}",
            kelebihan: ["Khusus anak-anak", "Penanganan mata juling"],
            deskripsi: `Layanan ini menangani gangguan penglihatan dan kelainan otot mata pada anak, termasuk mata juling (strabismus).`
        },
        rekonstruksi: {
            judul: "Rekonstruksi & Okuloplasti",
            gambar: "{{ asset('landing/assets/img/layanan/okuloplasti.jpg') }}",
            kelebihan: ["Perbaikan struktur kelopak mata", "Estetika sekitar mata"],
            deskripsi: `Menangani perbaikan struktur kelopak mata, saluran air mata, serta masalah estetika wajah di sekitar mata.`
        },
        imunologi: {
            judul: "Infeksi & Imunologi Mata",
            gambar: "{{ asset('landing/assets/img/layanan/imunologi.jpg') }}",
            kelebihan: ["Terapi infeksi mata", "Gangguan sistem imun mata"],
            deskripsi: `Menangani penyakit mata akibat infeksi atau gangguan sistem kekebalan tubuh, dengan pendekatan medis dan imunoterapi.`
        }
    };

    document.querySelectorAll('.layanan-item').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = layananData[btn.dataset.layanan];
            document.getElementById('judul-layanan').textContent = data.judul;
            document.getElementById('gambar-layanan').src = data.gambar;

            const list = document.getElementById('kelebihan-layanan');
            list.innerHTML = "";
            data.kelebihan.forEach(k => list.innerHTML += `<li>• ${k}</li>`);

            document.getElementById('deskripsi-layanan').textContent = data.deskripsi;
            document.getElementById('detail-layanan').classList.remove('d-none');

            // Scroll ke detail
            document.getElementById('detail-layanan').scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>
@endpush
