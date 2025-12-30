@extends('layouts.app')
@section('title', 'Dashboard | DecentraX Admin')
@section('ogtitle', 'Dashboard | DecentraX Admin')
@section('header')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h3 class="h3 mb-0 text-gray-800">Dashboard</h3>

        </div>

        <!-- Content Row -->
        <div class="row">


            <!-- Customers Card  -->

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Customers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $customers_count }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Card  -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Products</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $products_count }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Daily Supporting Bonus Overview</h6>

                    </div>
                    <livewire:daily-share-calculation-data-table />
                    <!-- Card Body -->
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('js/chart.js/Chart.min.js') }}"></script>
    <!-- sptoast JS-->
    <script src="{{ asset('js/sptoast.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
@endpush
