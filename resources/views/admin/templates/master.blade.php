<!DOCTYPE html>
<html lang="en">
<!-- head -->
@include('admin.templates._head')
<!-- /head -->
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dashboard/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo"
             height="60" width="60">
    </div>

    <!-- Navbar -->
    @include('admin.templates._header')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.templates._navbar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @include('admin.templates._breadcrumb')
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('admin.templates._footer')
</div>
<!-- ./wrapper -->

<!-- js -->
@include('admin.templates._js')
<!-- /js -->
</body>
</html>


