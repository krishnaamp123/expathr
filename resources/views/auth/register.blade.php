<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="{{ asset('storage/image/logokotakkecil.png') }}">

    <title>Register</title>


    <!-- Custom styles for this template -->
    <link href="{{asset('template-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

   <!-- Include Select2 CSS -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

   <!-- Include jQuery (required for Select2) -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <!-- Include Select2 JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

   <!-- Include jQuery (required for Select2) -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <!-- Include Select2 JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="{{asset('template-admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* Gaya khusus untuk halaman register */
        .register-select2 .select2-selection--single {
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 8px;
        }

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
            font-family: "Roboto Mono", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
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

        .select2-container .select2-dropdown,
        .select2-container .select2-results__option {
            font-size: 13px;
            font-family: "Roboto Mono", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }


        .custom-text-danger {
            font-size: 0.8rem; /* Adjust the font size as needed */
            color: #e74a3b; /* Ensure the color is still red */
        }

        .datepicker table tr td,
        .datepicker table tr td:hover {
            background-color: #fff !important;
            color: #231f20 !important;
        }

        .datepicker table tr td.today,
        .datepicker table tr td.today:hover {

            color: white !important;
        }

        .datepicker table tr td.active,
        .datepicker table tr td.active:hover {
            color: white !important;
        }

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
                                <img src="{{ asset('storage/image/expatlanding2.webp') }}" alt="Brand Icon" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-4">
                                    <hr class="my-4">
                                    <div class="text-center">
                                    @if(session('error'))
                                        <div class="text-danger text-center" style="color: #c03535;">{{ session('error') }}</div>
                                    @endif
                                    @if(session('success'))
                                        <div class="text-success text-center">{{session('success')}}</div>
                                    @endif
                                    <p class="login-box-msg">Sign up to start your session</p>
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
                                        <input name="email" type="email" class="form-control kaem-sub form-control-user"
                                               id="exampleInputEmail" aria-describedby="emailHelp"
                                               placeholder="Email">
                                        @error('email')
                                            <div class="custom-text-danger">{{$message}}</div>
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

                                    <div class="form-group">
                                        <input name="fullname" type="text" class="form-control kaem-sub form-control-user"
                                               id="exampleInputFullname" placeholder="Fullname">
                                        @error('fullname')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="nickname" type="text" class="form-control kaem-sub form-control-user"
                                               id="exampleInputNickname" placeholder="Nickname">
                                        @error('nickname')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="phone" type="number" class="form-control kaem-sub form-control-user"
                                               id="exampleInputPhone" placeholder="Phone">
                                        @error('phone')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="address" type="text" class="form-control kaem-sub form-control-user"
                                               id="exampleInputAddress" placeholder="Address">
                                        @error('address')
                                            <div class="custom-text-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input name="birth_date" type="text" class="form-control kaem-sub form-control-user"
                                               id="datepicker" placeholder="Birth Date">
                                        @error('birth_date')
                                            <div class="custom-text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <select name="gender" class="form-control select2 register-select2">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        @error('gender')
                                            <div class="custom-text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <hr class="sidebar-divider">
                                        <button type="submit" class="btn btn-user kaem-sub btn-block" style="background-color: #72A28A; color: white;">Sign Up</button>

                                        <hr>
                                        <div class="text-center my-3">
                                            <a class="small kaem-sub" href="{{route('login')}}" style="color: #72A28A;">Already have an account? Login!</a>
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

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('select[name="id_city"]').select2();
            $('select.register-select2').select2();
        });
    </script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('template-admin/js/sb-admin-2.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Menambahkan gaya kustom ketika datepicker muncul
            $('#datepicker').on('show', function () {
                $('.datepicker').css({
                    'background-color': '#fff',
                    'color': '#72A28A',
                    'border-radius': '8px'
                });
            });

             // Ensure the date is in the correct format before submitting
            $('#datepicker').on('changeDate', function (e) {
                var formattedDate = e.format('yyyy-mm-dd');
                $('#datepicker').val(formattedDate); // Update the input field value with the correct format
            });
        });
    </script>

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
