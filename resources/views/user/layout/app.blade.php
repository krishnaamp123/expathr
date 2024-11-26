    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <meta name="description" content="" />
            <meta name="author" content="" />
            <title>@yield('title')</title>

            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

            <!-- Include Select2 CSS -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

            <!-- Include jQuery (required for Select2) -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

            <!-- Include Select2 JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

            <!-- Core theme CSS (includes Bootstrap)-->
            <link href="{{ asset('template-user/css/styles.css') }}" rel="stylesheet" />

            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

            <!-- Favicon-->
            <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
            <!-- Font Awesome icons (free version)-->
            <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
            <!-- Google fonts-->
            <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />


            <style>
                .select2-container {
                    z-index: 10000 !important;
                    width: 100% !important;
                }

                .select2-dropdown {
                    z-index: 99999;
                }

                .select2-container .select2-selection--single {
                    height: 40px; /* Mengubah tinggi dari select */
                    padding: 8px; /* Mengatur padding agar lebih proporsional */
                    font-size: 14px; /* Ukuran font yang lebih besar */
                }

                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    line-height: 24px; /* Mengatur tinggi baris agar seimbang dengan ukuran input */
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 40px; /* Mengubah tinggi dari panah dropdown */
                }

                .select2-results__option--highlighted[aria-selected] {
                    background-color: #72A28A !important; /* Your desired hover color */
                    color: white; /* Text color when hovered */
                }

                /* Tambahkan padding atau margin untuk logo dan menu */
                .navbar-brand {
                    padding-left: 17px;
                }

                .navbar-nav {
                    padding-left: 17px;
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

                .datepicker-input {
                    padding-left: 10px; /* Sesuaikan nilai padding sesuai kebutuhan */
                }

                .city-item {
                    position: relative;
                    overflow: hidden;
                }

                .city-hover {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    opacity: 0;
                    transition: opacity 0.3s ease-in-out;
                }

                .city-item:hover .city-hover {
                    display: flex;
                    opacity: 1;
                }


            </style>

        </head>
        <body id="page-top">
            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-shrink" id="mainNav">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('getDashboardUser') }}"><img src="{{ asset('template-user/assets/img/expatlogo.png') }}" alt="..." /></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                        Menu
                        <i class="fas fa-bars ms-1"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                            <li class="nav-item"><a class="nav-link" href="#portfolio">Job</a></li>
                            <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="#store">Store</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('getTeam') }}">Team</a></li>
                            @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('getProfile') }}">
                                    <i class="fas fa-user"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('postLogout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="nav-link" href="{{ route('postLogout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </li>
                        @endauth
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Content -->
            @yield('content')
            <!-- Footer -->
            <footer class="footer py-4">
                <div class="divider2"></div>
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-4 text-lg-start" style="color: white">Copyright &copy; 2024 Expat. Roasters</div>
                        <div class="col-lg-4 my-3 my-lg-0">
                            <a class="btn btn-primary btn-social mx-2" href="#!" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-social mx-2" href="#!" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-social mx-2" href="#!" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a class="text-decoration-none me-3" style="color: white" href="#!">Privacy Policy</a>
                            <a class="text-decoration-none" style="color: white" href="#!">Terms of Use</a>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Core theme JS-->
            <script src="{{ asset('template-user/js/scripts.js') }}"></script>
            <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: "Select",
                        allowClear: false
                    });
                });
            </script>

            <!-- Bootstrap Datepicker JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

            <script>
                $(document).ready(function () {
                    // Inisialisasi datepicker untuk semua input dengan class .datepicker
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        todayHighlight: true
                    });

                    // Menambahkan gaya kustom ketika datepicker muncul
                    $('.datepicker').on('show', function () {
                        $('.datepicker').css({
                            'background-color': '#fff',
                            'color': '#231f20',
                            'border-radius': '8px'
                        });
                    });

                    // Ensure the date is in the correct format before submitting
                    $('.datepicker').on('changeDate', function (e) {
                        var formattedDate = e.format('yyyy-mm-dd');
                        $(this).val(formattedDate); // Update the input field value with the correct format
                    });
                });
            </script>

            {{-- <script>
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
            </script> --}}
            @yield('scripts')
        </body>
    </html>
