@extends('layouts.app')
@section('title', 'Milestone | DecentraX Admin')
@section('ogtitle', 'Milestone | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Milestone Management</h1>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('milestones.all') }}">Milestones</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        <!-- Content Row -->
        <div class="row ">
            <div class="col-xl-12 col-lg-12">
            <livewire:milestone.milestone-update-form :milestoneId="$id" />
            </div>

        </div>


    @endsection
    @push('scripts')
        <script></script>
    @endpush
