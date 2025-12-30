@extends('layouts.app')
@section('title', 'Product | DecentraX Admin')
@section('ogtitle', 'Product | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Product Management</h1>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.all') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        @include('pages.products.components.nav')
        <!-- Content Row -->
        <div class="row ">
            <div class="col-xl-12 col-lg-12">
            <livewire:product.product-update-form :productId="$id" />
            </div>

        </div>


    @endsection
    @push('scripts')
        <script></script>
    @endpush
