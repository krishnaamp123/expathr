<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/image/logokotakkecil.png') }}">

    <!-- Custom fonts for this template -->
    <link href="{{asset('template-admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('template-admin/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{asset('template-admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery (required for Select2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Include Datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        .select2-container {
            width: 100% !important;
        }
        .select2-container .select2-selection--single {
            height: 40px; /* Mengubah tinggi dari select */
            padding: 8px; /* Mengatur padding agar lebih proporsional */
            font-size: 14px; /* Ukuran font yang lebih besar */
        }

        .select2-dropdown {
            font-size: 14px; /* Ukuran teks dalam dropdown */
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

        .sidebar .nav-item.active .nav-link {
            background-color: #000; /* Warna hijau untuk latar belakang */
            color: #72A28A; /* Warna teks putih */
        }

        .sidebar .nav-item.active .nav-link i {
            color: #72A28A; /* Warna ikon putih saat aktif */
        }

        .sidebar .nav-item.active .nav-link:hover {
            background-color: #191919; /* Warna hijau gelap saat hover */
            color: white; /* Warna teks putih saat hover */
        }

        .sidebar .nav-item .nav-link:hover i {
            color: #fff; /* Warna ikon putih saat hover */
        }

        /* Mengubah warna background dan teks pada pagination */
        .table-responsive .pagination .page-item .page-link {
            background-color: #fff; /* Warna latar belakang pagination */
            color: #8f8f8f; /* Warna teks pagination */
        }

        .table-responsive .pagination .page-item .page-link:hover {
            background-color: #e7e7e7; /* Warna latar belakang saat hover */
            color: #72A28A; /* Warna teks saat hover */
        }

        /* Mengubah warna untuk pagination aktif */
        .table-responsive .pagination .page-item.active .page-link {
            background-color: #72A28A; /* Warna latar belakang untuk halaman aktif */
            color: white; /* Warna teks halaman aktif */
            border-color: #72A28A; /* Warna border halaman aktif */
        }

        .custom-checkbox {
            display: none; /* Sembunyikan checkbox asli */
        }

        .custom-checkbox-label {
            position: relative;
            padding-left: 20px; /* Ruang untuk ikon */
            cursor: pointer;
            font-size: 15px; /* Ukuran tulisan */
            color: #c03535; /* Warna tulisan */
        }

        .custom-checkbox-label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 15px; /* Ukuran checkbox */
            height: 15px; /* Ukuran checkbox */
            border: 1px solid #72A28A; /* Warna border */
            border-radius: 4px; /* Sudut melengkung */
            background-color: transparent; /* Warna latar belakang default */
            transition: background-color 0.2s, border-color 0.2s; /* Transisi saat tercentang */
        }

        .custom-checkbox:checked + .custom-checkbox-label::before {
            background-color: #c03535; /* Warna saat tercentang */
            border-color: #c03535; /* Warna border saat tercentang */
        }

        .custom-checkbox:checked + .custom-checkbox-label::after {
            content: 'X'; /* Ikon centang */
            position: absolute;
            left: 3.5px; /* Sesuaikan posisi ikon */
            top: 50%;
            transform: translateY(-50%);
            color: white; /* Warna ikon */
            font-size: 14px; /* Ukuran ikon */
        }

        .profile-user {
            width: 100px;
            height: 100px;
            object-fit: cover;
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

        label {
            font-size: 14px; /* Ini akan memengaruhi semua label di seluruh proyek */
        }



    </style>




</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color: #000; color: white;" id="accordionSidebar">

            <hr class="my-2">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-10">
                    <img src="{{ asset('storage/image/expatlogo.png') }}" alt="Brand Icon" style="">
                </div>
            </a>
            <hr class="my-2">

            <!-- Divider -->
            <hr class="sidebar-divider">
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Analitycs
            </div>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('getDashboardAdmin') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getDashboardAdmin') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(Auth::user()->role !== 'interviewer')
            <!-- Divider -->
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Master
            </div>

            <li class="nav-item {{ Request::routeIs('getUser') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getUser') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>User</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getHrjobCategory') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getHrjobCategory') }}">
                    <i class="fas fa-fw fa-cubes "></i>
                    <span>Job Category</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getOutlet') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getOutlet') }}">
                    <i class="fas fa-fw fa-building "></i>
                    <span>Placement</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getHrjob') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getHrjob') }}">
                    <i class="fas fa-fw fa-suitcase"></i>
                    <span>Job</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getQuestion') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getQuestion') }}">
                    <i class="fas fa-fw fa-question-circle "></i>
                    <span>Question</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getForm') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getForm') }}">
                    <i class="fas fa-fw fa-clipboard "></i>
                    <span>Form</span>
                </a>
            </li>
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Main
            </div>

            <li class="nav-item {{ Request::routeIs('getUserHrjob') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getUserHrjob') }}">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>User Job</span>
                </a>
            </li>

            @if(Auth::user()->role !== 'interviewer')
            <li class="nav-item {{ Request::routeIs('getAnswer') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getAnswer') }}">
                    <i class="fas fa-fw fa-file"></i>
                    <span>Answer</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getInterview') ? 'active' : '' }} {{ Request::routeIs('getUserInterview') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getInterview') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Interview</span>
                </a>
            </li>
            @endif

             <!-- Divider -->
             <hr class="sidebar-divider">

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #fff; color: black;" >

                    <!-- Sidebar Toggle (Topbar) -->
                    <div class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt" style="color: #5a5c69;"> Logout</i>
                                </a>
                            </li>
                        @endauth

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Expat. Roasters 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
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

    <!-- Bootstrap core JavaScript-->
    {{-- <script src="{{asset('template-admin/vendor/jquery/jquery.min.js')}}"></script> --}}
    <script src="{{asset('template-admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('template-admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('template-admin/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{asset('template-admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('template-admin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('template-admin/js/demo/datatables-demo.js')}}"></script>

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
        $(document).ready(function() {
            // Inisialisasi Select2 untuk elemen di luar modal
            $('.select2:not(.inside-modal)').select2({
                placeholder: "Select",
                allowClear: true
            });

            // Inisialisasi Select2 untuk elemen di dalam modal
            $('.select2.inside-modal').each(function () {
                $(this).select2({
                    placeholder: "Select",
                    allowClear: true,
                    dropdownParent: $(this).closest('.modal')
                });
            });
        });
    </script>

@yield('scripts')
</body>

</html>
