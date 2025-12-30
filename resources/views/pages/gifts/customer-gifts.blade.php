@extends('layouts.app')
@section('title', 'Gift Purchases | DecentraX Admin')
@section('ogtitle', 'Gift Purchases | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gift Purchases</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gift Purchases</li>
            </ol>
        </nav>



        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <livewire:gift.gift-purchase-data-table />
                {{-- <livewire:setting-update-form /> --}}
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
