<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('template-admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('template-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

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
                                <img src="{{ asset('storage/image/expatlanding.png') }}" alt="Brand Icon" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-4">
                                    <hr class="my-5">
                                    <div class="text-center">
                                    @if(session('error'))
                                        <div class="text-danger text-center" style="color: #c03535;">{{ session('error') }}</div>
                                    @endif
                                    @if(session('success'))
                                        <div class="text-success text-center">{{session('success')}}</div>
                                    @endif
                                    <p class="login-box-msg">Sign in to start your session</p>
                                        {{-- <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1> --}}
                                    </div>
                                    <form class="user" action="{{ route('postLogin') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control form-control-user"
                                               id="exampleInputEmail" aria-describedby="emailHelp"
                                               placeholder="Email">
                                        @error('email')
                                            <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input name="password" type="password" class="form-control form-control-user"
                                               id="exampleInputPassword" placeholder="Password">
                                        @error('password')
                                            <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <hr class="sidebar-divider">
                                        <button type="submit" class="btn btn-user btn-block" style="background-color: #72A28A; color: white;">Sign In</button>
                                    </form>

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

</body>

</html>
