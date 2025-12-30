@extends('layouts.app')
@section('title', 'Reports | DecentraX Admin')
@section('ogtitle', 'Reports | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Reports</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav>



        <!-- Content Row -->

        <div class="row">
            <livewire:reports.reports-table />
        </div>

    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
