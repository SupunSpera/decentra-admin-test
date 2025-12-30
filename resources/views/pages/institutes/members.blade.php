@extends('layouts.app')
@section('title', 'Institute Members | DecentraX Admin')
@section('ogtitle', 'Institute Members | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Institute Members</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#"> {{ $institute->referral_code }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Institute Members</li>
            </ol>
        </nav>
        @include('pages.institutes.components.nav')

        <!-- Content Row -->
        {{-- <div class="row">
            <!-- Active Members  -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Active Members</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ count($activeInstitutes) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="far fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pending Members  -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Members</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ count($pendingInstitutes) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="far fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <a class="btn btn-secondary" href="{{ route('customers.new') }}"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div> --}}

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <livewire:institute-members-data-table :institute="$institute"/>
            </div>

        </div>

    </div>
@endsection

