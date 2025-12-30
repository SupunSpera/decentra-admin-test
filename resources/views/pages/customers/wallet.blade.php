@extends('layouts.app')
@section('title', 'Customer | DecentraX Admin')
@section('ogtitle', 'Customer | DecentraX Admin')
@section('header')


@section('content')

    <style>
        .right-side-panel {
            position: fixed;
            top: 0;
            right: -500px;
            width: 500px;
            height: 100vh;
            /* Ensures full screen height */
            background-color: white;
            /* Change background color as needed */
            z-index: 2000;
        }

        @media (max-width: 430px) {
            .right-side-panel {
                width: 423px !important;
            }

        }
    </style>

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
                <li class="breadcrumb-item active" aria-current="page">Wallet</li>
            </ol>
        </nav>
        @include('pages.customers.components.nav')
        <div class="row">
            <!--  Wallet Balance  -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Wallet Balance</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    USDT {{ $wallet->usdt_amount }} ( URBX {{ $wallet->token_amount }} )
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Frozen Tokens  -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Frozen Tokens</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    URBX {{ $customer->frozen_shares > 0 ? $customer->frozen_shares : 0 }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <a style="cursor: pointer" onclick="toggleSlider('TOKEN')"> <i
                                        class="fas fa-plus fa-2x text-primary "></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Content Row -->
        <div class="row col-12">
            <div class="col-12">
                <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                    <li class="nav-item waves-effect waves-light mb-2 mt-2 second-nav">
                        <a class="nav-link border border-primary rounded active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true"> <span class="d-block d-md-none"><i
                                    class="fa fa-wallet"></i></span>
                            <span class="d-none d-md-block">
                                <i class="fa fa-wallet"></i> Wallet Transactions
                            </span></a>
                    </li>
                    &nbsp;&nbsp;
                    <li class="nav-item waves-effect waves-light mb-2 mt-2 second-nav">
                        <a class="nav-link border border-primary rounded" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">
                            <span class="d-block d-md-none"><i class="fa fa-solid fa-user-lock"></i></span>
                            <span class="d-none d-md-block">
                                <i class="fa fa-solid fa-user-lock"></i> Frozen Tokens
                            </span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="col-xl-12 col-lg-12">

                            <div class="row">
                                <div class="col-12 text-right">
                                    <a class="btn btn-secondary " onclick="toggleSlider('USDT')"><i class="fa fa-plus"></i>
                                        Deposit</a>
                                </div>
                            </div>
                            <livewire:wallet.wallet-transaction-data-table :customer_id="$id" />
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <livewire:wallet.frozen-token-data-table :customer_id="$id" />
                    </div>
                </div>
            </div>

            <livewire:wallet.wallet-slider :customer_id="$id" />

        </div>




    @endsection

    @push('scripts')
        <script>
            var rightSidePanel = $('.right-side-panel');
            var isPanelVisible = false; // Flag to track panel visibility

            $('.slide-button').click(function() {


                if (!isPanelVisible) {
                    rightSidePanel.animate({
                        right: '0' // Slide right panel out of view
                    }, 500, function() {
                        isPanelVisible = true; // Update visibility flag
                    });
                } else {

                    rightSidePanel.animate({
                        right: '-500px' // Slide right panel back into view
                    }, 500, function() {
                        isPanelVisible = false; // Update visibility flag
                    });
                }
            });

            function toggleSlider(type) {

                if (type == "USDT") {
                    Livewire.emitTo('wallet.wallet-slider', 'setType', 'USDT')
                } else if (type == "TOKEN") {
                    Livewire.emitTo('wallet.wallet-slider', 'setType', 'TOKEN')
                }

                if (!isPanelVisible) {
                    rightSidePanel.animate({
                        right: '0' // Slide right panel out of view
                    }, 500, function() {
                        isPanelVisible = true; // Update visibility flag
                    });
                } else {

                    rightSidePanel.animate({
                        right: '-500px' // Slide right panel back into view
                    }, 500, function() {
                        isPanelVisible = false; // Update visibility flag
                    });
                }
            }
        </script>
    @endpush
