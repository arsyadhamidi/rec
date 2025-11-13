<div class="row">
    <div class="col-lg-6">
        <div class="mb-3">
            <h3 class="dashboard-title">Dashboard</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-left: -15px">
                    <li class="breadcrumb-item"><a href="/dashboard" class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Halaman Utama</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8 my-5">
                        <div class="mb-3 py-5">
                            <h1 class="welcome-text">Selamat Datang di <span class="text-primary">SIMRS REC</span></h1>
                            <h4 class="user-greeting">Halo, <span>{{ Auth()->user()->name ?? 'Pengguna' }}</span>. Kami
                                senang Anda kembali!</h4>
                            <p>Jelajahi fitur kami dan kelola kebutuhan Anda dengan lebih mudah.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <img src="{{ asset('images/dashboard.png') }}" class="img-fluid" alt="Dashboard Illustration">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
