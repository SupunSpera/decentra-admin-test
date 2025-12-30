@extends('layouts.app')
@section('title', 'Customer | DecentraX Admin')
@section('ogtitle', 'Customer | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Customer Management</h1>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customers.all') }}">Customers</a></li>
                <li class="breadcrumb-item"><a href="#">{{ $customer->referral_code }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        @include('pages.customers.components.nav')
        <!-- Content Row -->
        <div class="row col-12">
            <div class="col-12 col-lg-4">
                <img src="https://picsum.photos/200/300" alt="customer pic">
            </div>
            <div class="col-12 col-lg-8">
                <livewire:customer.customer-update-form :customerId="$id"/>
            </div>


        </div>


    @endsection

    @push('scripts')
        <script></script>
    @endpush
