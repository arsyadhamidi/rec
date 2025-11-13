<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | RSKM Regina Eye Center</title>
    <!-- base:css -->
    <link rel="stylesheet"
          href="{{ asset('admin/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet"
          href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet"
          href="{{ asset('admin/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon"
          href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet"
          href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{ asset('images/logo-1.png') }}"
                                     alt="logo">
                            </div>
                            <h4>Selamat Datang kembali!</h4>
                            <h6 class="font-weight-light">Silakan masuk ke akun Anda.</h6>
                            <form class="pt-3"
                                  method="POST"
                                  action="{{ route('login.authenticate') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text"
                                           name="username"
                                           class="form-control form-control-lg @error('username') is-invalid @enderror"
                                           placeholder="Username"
                                           value="{{ old('username') }}">
                                    @error('username')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group position-relative">
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control form-control-lg pr-5 @error('password') is-invalid @enderror"
                                           placeholder="Password">
                                    <span toggle="#password"
                                          class="fa fa-fw fa-eye field-icon toggle-password"
                                          style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></span>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Masuk</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <a href="#"
                                       class="auth-link text-black">Lupa password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>
    <script src="{{ asset('admin/js/settings.js') }}"></script>
    <script src="{{ asset('admin/js/todolist.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery-min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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
    <script>
        $(document).on("click", ".toggle-password", function() {
            let input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $(this).removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                $(this).removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    </script>
    <!-- endinject -->
</body>

</html>
