<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'DecentraX Admin ')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('description', 'This is DecentraX Admin ')" />

    <meta property="og:title" content="@yield('ogtitle', 'DecentraX Admin')" />
    <meta property="og:url" content="{{ Request::url() }}" />

    <meta property="og:type" content="@yield('ogtype', 'website')" />

    <meta property="og:image" content="@yield('ogimage', asset('assets/images/logo.svg'))" />
    <meta property="og:image:secure_url" content="@yield('ogimage', asset('assets/images/logo.svg'))" />
    <meta property="og:image:width" content="@yield('ogimagewidth', 500)" />
    <meta property="og:image:height" content="@yield('ogimageheight', 200)" />

    <meta name="author" content="">

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    @include('includes.styles')

    {{-- @livewireStyles --}}

    <!-- Custom fonts for this template-->
    <link href="{{ asset('css/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    @php
        $curr_url = Route::currentRouteName();
    @endphp
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('pages.components.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('pages.components.nav')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('pages.components.footer')
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Sure, Logout</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    @include('includes.scripts')

</body>

</html>
