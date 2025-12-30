@extends('layouts.app')
@section('title', 'Items | DecentraX Admin')
@section('ogtitle', 'Items | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="mb-4 d-sm-flex align-items-center justify-content-between">
            <h1 class="mb-0 text-gray-800 h3">Item Management</h1>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.all') }}">Items</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>
        <!-- Content Row -->
        <div class="row col-12">
            {{-- <div class="col-4">
                <img src="https://picsum.photos/200/300" alt="customer pic">
            </div> --}}
            <div class="col-12">
                <livewire:item.item-create-form />
            </div>


        </div>


    @endsection
    @push('scripts')
        <script></script>
    @endpush
