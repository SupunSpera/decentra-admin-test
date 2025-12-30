@extends('layouts.app')
@section('title', 'Institutes | DecentraX Admin')
@section('ogtitle', 'Institutes | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="mb-4 d-sm-flex align-items-center justify-content-between">
            <h1 class="mb-0 text-gray-800 h3">Institutes</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Institutes</li>
            </ol>
        </nav>

        <!-- Content Row -->
        <div class="row">
            <!-- Active Institutes  -->
            <div class="mb-4 col-xl-3 col-md-6">
                <div class="py-2 shadow card border-left-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                                    Active Institutes</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                    {{ count($activeInstitutes) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 far fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pending Institutes  -->
            <div class="mb-4 col-xl-3 col-md-6">
                <div class="py-2 shadow card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="mr-2 col">
                                <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                                    Pending Institutes</div>
                                <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                    {{ count($pendingInstitutes) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="text-gray-300 far fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="text-right col-12">
                {{-- <a class="btn btn-secondary" href="{{ route('customers.new') }}"><i class="fa fa-plus"></i> Add New</a> --}}
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <livewire:institutes-data-table />
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function validateAndApprove(id) {
            const frozenAssetInput = document.getElementById('frozen_asset_' + id);
            const errorSpan = document.getElementById('frozen_asset_error_' + id);
            const frozenAssetValue = frozenAssetInput.value;

            if (!frozenAssetValue) {
                errorSpan.innerText = "Frozen Asset amount is required.";
                return; // Prevent the modal from closing and Livewire from emitting the event
            } else {
                errorSpan.innerText = ""; // Clear any previous error message
                approveInstitute(id); // Call the original approveInstitute function
            }
        }

        function approveInstitute(id) {
            $.confirm({
                title: 'Are You Sure?',
                content: "Do You Want To Approve This?",
                autoClose: 'cancel|8000',
                type: 'orange',
                confirmButton: "Yes",
                cancelButton: "Cancel",
                theme: 'material',
                backgroundDismiss: false,
                backgroundDismissAnimation: 'glow',
                buttons: {
                    tryAgain: {
                        text: "Yes, Approve It ",
                        action: function() {
                            Livewire.emit('approveInstitute', id)
                            // Close the modal using vanilla JavaScript
                            const approveModal = document.getElementById('approveInstituteModal_' + id);
                            approveModal.classList.remove('show');
                            approveModal.style.display = '';
                        }
                    },
                    cancel: function() {}
                }
            });
        }
    </script>
@endpush
