<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Custom fonts for this template -->
    <link href="{{asset('template-admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('template-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{asset('template-admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery (required for Select2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 40px; /* Mengubah tinggi dari select */
            padding: 8px; /* Mengatur padding agar lebih proporsional */
            font-size: 16px; /* Ukuran font yang lebih besar */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px; /* Mengatur tinggi baris agar seimbang dengan ukuran input */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px; /* Mengubah tinggi dari panah dropdown */
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



        /* Change the hover color of the dropdown items in Select2 */
        .select2-results__option--highlighted[aria-selected] {
            background-color: #72A28A !important; /* Your desired hover color */
            color: white; /* Text color when hovered */
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
                {{-- <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div> --}}
            </a>
            <hr class="my-2">
            <!-- Divider -->
            <hr class="sidebar-divider">

            {{-- <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider">

            {{-- <!-- Heading -->
            <div class="sidebar-heading">
                Master
            </div>

            <li class="nav-item {{ Request::routeIs('getCompany') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getCompany') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Company</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getUser') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getUser') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>User</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getPackaging') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getPackaging') }}">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Packaging</span>
                </a>
            </li>

            <li class="nav-item {{ Request::routeIs('getProduct') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('getProduct') }}">
                    <i class="fas fa-fw fa-shopping-bag"></i>
                    <span>Product</span>
                </a>
            </li> --}}

             <!-- Divider -->
             <hr class="sidebar-divider">

             {{-- <!-- Heading -->
             <div class="sidebar-heading">
                 Main
             </div>

             <li class="nav-item {{ Request::routeIs('getCustomerProduct') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('getCustomerProduct') }}">
                     <i class="fas fa-fw fa-tags"></i>
                     <span>CustomerProduct</span>
                 </a>
             </li>

             <li class="nav-item {{ Request::routeIs('getComplaint') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('getComplaint') }}">
                     <i class="fas fa-fw fa-exclamation-triangle"></i>
                     <span>Complaint</span>
                 </a>
             </li>

             <li class="nav-item {{ Request::routeIs('getOrder') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('getOrder') }}">
                     <i class="fas fa-fw fa-credit-card"></i>
                     <span>Order</span>
                 </a>
             </li> --}}

             <!-- Divider -->
             <hr class="sidebar-divider">

             {{-- <!-- Heading -->
             <div class="sidebar-heading">
                 Supermarket
             </div>

             <li class="nav-item {{ Request::routeIs('getStock') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('getStock') }}">
                     <i class="fas fa-fw fa-cubes"></i>
                     <span>Stock</span>
                 </a>
             </li>

             <li class="nav-item {{ Request::routeIs('getDetailStock') ? 'active' : '' }}">
                 <a class="nav-link" href="{{ route('getDetailStock') }}">
                     <i class="fas fa-fw fa-history"></i>
                     <span>Detail Stock</span>
                 </a>
             </li> --}}

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
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('storage/image/adminicon.png') }}">
                            </a>
                        </li>

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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select",
                allowClear: true
            });
        });
    </script>

@yield('scripts')
</body>

</html>
