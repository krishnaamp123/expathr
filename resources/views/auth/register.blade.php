<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom styles for this template -->
    <link href="{{asset('template-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

   <!-- Include Select2 CSS -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

   <!-- Include jQuery (required for Select2) -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <!-- Include Select2 JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="{{asset('template-admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('template-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        .select2-container .select2-selection--single {
            display: block;
            width: 100%;
            height: 40px;
            font-weight: 400;
            line-height: 1.5;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            font-size: 0.8rem;
            border-radius: 10rem;
            padding-top: 0.5rem;
            padding-bottom: 2.5rem;
            padding-left: 0.6rem;
            padding-right: 0.6rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            /* line-height: 5px; */
            line-height: 1.5; Adjust line-height
            padding-left: 0.75rem; /* Add padding to the left */
            padding-right: 0.75rem; /* Add padding to the right */
            padding-top: 0.375rem; /* Add padding to the top */
            padding-bottom: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%; /* Adjust height to 100% */
            top: 50%; /* Center the arrow vertically */
            transform: translateY(-50%); /* Center the arrow vertically */
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: #72A28A !important; /* Your desired hover color */
            color: white; /* Text color when hovered */
        }

        .custom-text-danger {
            font-size: 0.8rem; /* Adjust the font size as needed */
            color: #e74a3b; /* Ensure the color is still red */
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
                                <img src="{{ asset('storage/image/expatlanding2.png') }}" alt="Brand Icon" style="width: 100%; height: 100%; object-fit: cover;">
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
                                    <p class="login-box-msg">Sign up to start your session</p>
                                        {{-- <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1> --}}
                                    </div>
                                    <form class="user" action="{{ route('postRegister') }}" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <select name="id_city" class="form-control select2">
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_city')
                                            <div class="custom-text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control form-control-user"
                                               id="exampleInputEmail" aria-describedby="emailHelp"
                                               placeholder="Email">
                                        @error('email')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="password" type="password" class="form-control form-control-user"
                                               id="exampleInputPassword" placeholder="Password">
                                        @error('password')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="fullname" type="text" class="form-control form-control-user"
                                               id="exampleInputFullname" placeholder="Fullname">
                                        @error('fullname')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="nickname" type="text" class="form-control form-control-user"
                                               id="exampleInputNickname" placeholder="Nickname">
                                        @error('nickname')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="phone" type="number" class="form-control form-control-user"
                                               id="exampleInputPhone" placeholder="Phone">
                                        @error('phone')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="address" type="text" class="form-control form-control-user"
                                               id="exampleInputAddress" placeholder="Address">
                                        @error('address')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <hr class="sidebar-divider">
                                        <button type="submit" class="btn btn-user btn-block" style="background-color: #72A28A; color: white;">Sign Up</button>

                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="{{route('login')}}" style="color: #72A28A;">Already have an account? Login!</a>
                                        </div>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="id_city"]').select2();
        });
    </script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('template-admin/js/sb-admin-2.min.js')}}"></script>

</body>

</html>
