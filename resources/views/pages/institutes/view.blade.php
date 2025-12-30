@extends('layouts.app')
@section('title', 'Institute Management | DecentraX Admin')
@section('ogtitle', 'Institute Management | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Institute Management</h1>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('institutes.all') }}">Institutes</a></li>
                <li class="breadcrumb-item"><a href="#"> {{ $institute->referral_code }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
        @if ($institute->status == App\Models\Customer::STATUS['ACTIVE'])
            @include('pages.institutes.components.nav')
        @endif
        <!-- Content Row -->
        <div class="row col-12">
            {{-- <div class="col-4">
                <img src="https://picsum.photos/200/300" alt="institute pic">
            </div> --}}
            <div class="col-12">
                <form class="text-dark">

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                                value="{{ $instituteDetail ? $instituteDetail->name : 'N/A' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                                value="{{ $instituteDetail ? $instituteDetail->address : 'N/A' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Telephone</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                                value="{{ $institute->telephone }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Mobile</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                                value="{{ $institute->mobile }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email </label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                                value="{{ $institute->email }}">
                        </div>
                    </div>
                    <div class="form-group
                                row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Referral </label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail"
                                value="{{ $directReferralCustomer ? $directReferralCustomer->email : 'N/A' }}">
                        </div>
                    </div>

                </form>
            </div>
        </div>

    @endsection
    @push('styles')
        <style>
            body {
                height: 100vh;
            }
        </style>
    @endpush
    @push('scripts')
        <script></script>
    @endpush
