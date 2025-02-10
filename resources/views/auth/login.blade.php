<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="{{ asset('storage/image/logokotakkecil.png') }}">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('template-admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('template-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        .kaem-sub {
            font-weight: 400;
            font-family: "Roboto Mono", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        .login-box-msg {
            font-size: 0.8rem;
            font-weight: 400;
            font-family: "Roboto Mono", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

    </style>
</head>

<body style="background-color: black;">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background: white;">
                                <img src="{{ asset('storage/image/expatlanding.webp') }}" alt="Brand Icon" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-4">
                                    <hr class="my-5">
                                    <div class="text-center">
                                    @if(session('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if(session('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <p class="login-box-msg">Sign in to start your session</p>
                                    </div>
                                    <form class="user" action="{{ route('postLogin') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control kaem-sub form-control-user"
                                               id="exampleInputEmail" aria-describedby="emailHelp"
                                               placeholder="Email">
                                        @error('email')
                                            <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group position-relative">
                                        <input name="password" type="password" class="form-control kaem-sub form-control-user"
                                               id="exampleInputPassword" placeholder="Password">
                                        <i class="fa fa-eye position-absolute" id="togglePassword"
                                           style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                        @error('password')
                                            <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <hr class="sidebar-divider">
                                        <button type="submit" class="btn btn-user kaem-sub btn-block" style="background-color: #72A28A; color: white;">Sign In</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small kaem-sub" href="{{route('getRegister')}}" style="color: #72A28A;">Create an Account!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small kaem-sub" href="{{route('password.request')}}" style="color: #72A28A;">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('template-admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('template-admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('template-admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('template-admin/js/sb-admin-2.min.js')}}"></script>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            var passwordInput = document.getElementById("exampleInputPassword");
            var icon = this;
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    </script>

</body>

</html>
