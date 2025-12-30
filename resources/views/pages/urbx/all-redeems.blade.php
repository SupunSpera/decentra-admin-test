@extends('layouts.app')
@section('title', 'URBX Redeems | DecentraX Admin')
@section('ogtitle', 'URBX Redeems | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">URBX Redeems</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">URBX Redeems</li>
            </ol>
        </nav>
        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">

                <livewire:wallet.urbx-redeem-data-table>

            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function approveURBXWithdrawal(id) {
            $.confirm({
                title: 'Are You Sure?',
                content: "Do You Want To Approve This?",
                autoClose: 'cancel|8000',
                type: 'red',
                confirmButton: "Yes",
                cancelButton: "Cancel",
                theme: 'material',
                backgroundDismiss: false,
                backgroundDismissAnimation: 'glow',
                buttons: {
                    tryAgain: {
                        text: "Yes, Approve It ",
                        action: function() {
                            Livewire.emit('approveURBXWithdrawal', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }

        function rejectURBXWithdrawal(id) {
            $.confirm({
                title: 'Are You Sure?',
                content: "Do You Want To Reject This?",
                autoClose: 'cancel|8000',
                type: 'red',
                confirmButton: "Yes",
                cancelButton: "Cancel",
                theme: 'material',
                backgroundDismiss: false,
                backgroundDismissAnimation: 'glow',
                buttons: {
                    tryAgain: {
                        text: "Yes, Reject It ",
                        action: function() {
                            Livewire.emit('rejectURBXWithdrawal', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }


    </script>
@endpush
