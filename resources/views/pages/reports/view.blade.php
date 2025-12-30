@extends('layouts.app')
@section('title', 'Reports | DecentraX Admin')
@section('ogtitle', 'Reports | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                @switch($reportId)
                    @case('product_purchases')
                        {{ 'Token Buyers Report' }}
                    @break

                    @default
                        {{ 'Reports' }}
                @endswitch

            </h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav>



        <!-- Content Row -->
        <div class="row">
            <div class="col-12">
                @switch($reportId)
                    @case('product_purchases')
                         <livewire:reports.previews.product-purchase-data-table :startDate="$startDate" :endDate="$endDate" />
                    @break


                @endswitch


            </div>
        </div>



    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
