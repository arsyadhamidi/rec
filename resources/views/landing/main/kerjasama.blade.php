<!-- ========================== -->
<!-- 7. ASURANSI REKANAN SECTION -->
<!-- ========================== -->
<section id="asuransi-rekanan"
         class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase text-primary">
                Asuransi <span class="text-orange">Rekanan</span>
            </h2>
            <p class="text-muted">
                RSKM Regina Eye Center bekerja sama dengan berbagai perusahaan asuransi dan instansi untuk kemudahan pelayanan pasien.
            </p>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Kelompok 1 -->
            <div class="col-md-12">
                <div class="d-flex justify-content-center flex-wrap gap-4 align-items-center">
                    @foreach ($kerjas as $kerja)
                        <a href="#">
                            <img src="{{ asset('storage/' . $kerja->foto_kerjasama) }}"
                                 alt="{{ $kerja->nm_kerjasama ?? 'Gambar' }}"
                                 height="150">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
