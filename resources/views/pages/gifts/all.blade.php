@extends('layouts.app')
@section('title', 'Gifts | DecentraX Admin')
@section('ogtitle', 'Gifts | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gifts</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gifts</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <!-- Active Gifts  -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Active Gifts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                 {{ count($gifts)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gift fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Deleted Gifts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-12 text-right">
                <a class="btn btn-secondary" href="{{ route('gifts.new') }}"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <livewire:gift.gift-data-table />
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function deleteGift(id) {
            $.confirm({
                title: 'Are You Sure?',
                content: "Do You Want To Remove This?",
                autoClose: 'cancel|8000',
                type: 'red',
                confirmButton: "Yes",
                cancelButton: "Cancel",
                theme: 'material',
                backgroundDismiss: false,
                backgroundDismissAnimation: 'glow',
                buttons: {
                    tryAgain: {
                        text: "Yes, Delete It ",
                        action: function() {
                            Livewire.emit('deleteGift', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }

        function publishGift(id) {
            $.confirm({
                title: 'Are You Sure?',
                content: "Do You Want To Publish This?",
                autoClose: 'cancel|8000',
                type: 'orange',
                confirmButton: "Yes",
                cancelButton: "Cancel",
                theme: 'material',
                backgroundDismiss: false,
                backgroundDismissAnimation: 'glow',
                buttons: {
                    tryAgain: {
                        text: "Yes, Publish It ",
                        action: function() {
                            Livewire.emit('publishGift', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }

        function unPublishGift(id) {
            $.confirm({
                title: 'Are You Sure?',
                content: "Do You Want To Unpublish This?",
                autoClose: 'cancel|8000',
                type: 'orange',
                confirmButton: "Yes",
                cancelButton: "Cancel",
                theme: 'material',
                backgroundDismiss: false,
                backgroundDismissAnimation: 'glow',
                buttons: {
                    tryAgain: {
                        text: "Yes, Unpublish It ",
                        action: function() {
                            Livewire.emit('unpublishGift', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }


        window.addEventListener('delete-failed', event => {

            $.alert({
                title: 'Error!',
                content: 'Somthing went wrong!',
                autoClose: 'cancel|8000',
                type: 'red',
            });

        })
    </script>
@endpush
