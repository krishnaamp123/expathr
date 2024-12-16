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
                    /* z-index: 10000 !important; */
                    width: 100% !important;
                }

                /* .select2-dropdown {
                    z-index: 99999;
                } */

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

                .select2-container--default .select2-selection--single .select2-selection__clear {
                    right: 24px; /* Geser posisi clear ke kiri */
                    font-size: 12px; /* Sesuaikan ukuran font jika perlu */
                    color: #666; /* Warna tombol clear */
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

                .container.d-flex {
                    display: flex;
                    flex-direction: row;
                }

                .filter-section {
                    flex: 0 0 20%; /* Atur lebar sidebar */
                    max-width: 20%;
                }

                .content-section {
                    flex: 1; /* Ambil sisa ruang */
                    text-align: left;
                    margin: 0;
                    padding: 0;
                }

                .custom-nav {
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 10px;
                }

                .custom-nav .nav-item {
                    margin-bottom: 5px;
                }

                .custom-nav .nav-link {
                    color: #fff;
                    text-align: left;
                    padding: 8px 15px;
                    border: 1px solid transparent;
                    border-radius: 4px;
                }

                .custom-nav .nav-link.active {
                    background-color: #72A28A;
                    color: white;
                    border-color: #72A28A;
                }

                @media (max-width: 768px) {
                    .container.d-flex {
                        flex-direction: column;
                    }

                    .filter-section {
                        max-width: 100%;
                        flex: none;
                        margin-bottom: 15px;
                    }

                    .content-section {
                        max-width: 100%;
                    }
                }

                .d-none {
                    display: none !important;
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
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('getVacancy') ? 'active' : '' }}" href="{{ route('getVacancy') }}">Job</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('getMyVacancy') ? 'active' : '' }}" href="{{ route('getMyVacancy') }}">My Job</a>
                            </li>
                            @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('getProfile') ? 'active' : '' }}" href="{{ route('getProfile') }}">
                                    <i class="fas fa-user"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt" style="color: #fff;"> Logout</i>
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
                            <a class="btn btn-primary btn-social mx-2" href="https://www.instagram.com/expatroasters" aria-label="Twitter"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-primary btn-social mx-2" href="https://www.facebook.com/expatroasters" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-social mx-2" href="https://www.linkedin.com/company/expat-roasters" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a class="text-decoration-none" style="color: white" href="https://expatroasters.com/pages/terms">Terms and Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                            <button class="close" style="color: white" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Select "Logout" below if you are ready to end your current session.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <form id="logout-form" action="{{ route('postLogout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Core theme JS-->
            <script src="{{ asset('template-user/js/scripts.js') }}"></script>
            <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

            <script>
                $(document).ready(function () {
                    $('.select2').each(function () {
                        $(this).select2({
                            placeholder: "Select an option",
                            allowClear: false,
                            dropdownParent: $(this).closest('.modal')
                        });
                    });
                    // Inisialisasi Select2 untuk elemen cityFilter dan categoryFilter
                    $('#cityFilter, #categoryFilter').select2({
                        placeholder: "Select an option",
                        allowClear: false
                    });

                    // Inisialisasi Select2 dengan allowClear hanya untuk elemen tertentu
                    $('#platform').select2({
                        placeholder: "Select an option",
                        allowClear: true,
                        dropdownParent: $('#addSourceModal')
                    });

                    $('#editplatform').select2({
                        placeholder: "Select an option",
                        allowClear: true,
                        dropdownParent: $('#editSourceModal')
                    });
                });
                // $(document).ready(function () {
                //     $('#cityFilter, #categoryFilter').select2({
                //         placeholder: "Select an option",
                //         allowClear: false
                //     });
                // });
            </script>

            <script>
                $(document).ready(function () {
                    // Filter and Search Functionality
                    $('#cityFilter, #categoryFilter, #searchBar').on('change keyup', function () {
                        const selectedCity = $('#cityFilter').val();
                        const selectedCategory = $('#categoryFilter').val();
                        const searchText = $('#searchBar').val().toLowerCase();

                        $('.portfolio-item').each(function () {
                            const cityId = $(this).data('city-id');
                            const categoryId = $(this).data('category-id');
                            const jobName = $(this).find('.portfolio-caption-heading').text().toLowerCase();

                            const matchesCity = selectedCity === '' || cityId == selectedCity;
                            const matchesCategory = selectedCategory === '' || categoryId == selectedCategory;
                            const matchesSearch = searchText === '' || jobName.includes(searchText);

                            if (matchesCity && matchesCategory && matchesSearch) {
                                $(this).addClass('active-item').show();
                            } else {
                                $(this).removeClass('active-item').hide();
                            }
                        });
                    });
                });

                function resetFilters() {
                    $('#cityFilter').val('');
                    $('#categoryFilter').val('');
                    $('#searchBar').val('');
                    $('#cityFilter, #categoryFilter, #searchBar').trigger('change');
                }
            </script>


            @yield('scripts')
        </body>
    </html>
