@extends('layouts.app')
@section('title', 'Withdrawals | DecentraX Admin')
@section('ogtitle', 'Withdrawals | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Withdrawals</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pending Withdrawals</li>
            </ol>
        </nav>




        <div class="row">
            <div class="col-12 text-start">
                <a class="btn btn-sm btn-warning" href="{{ route('withdrawals.pending') }}"> To Be Approved</a>
                <a class="btn btn-sm btn-outline-info" href="{{ route('withdrawals.approved') }}"> To Be Send</a>
                <a class="btn btn-sm btn-outline-success" href="{{ route('withdrawals.sent') }}"> Sent</a>
                <a class="btn btn-sm btn-outline-danger" href="{{ route('withdrawals.rejected') }}"> Rejected</a>
            </div>
        </div>
        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">

                <livewire:withdrawal.pending-withdrawals-data-table>

            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
        function approveWithdrawal(id) {
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
                            Livewire.emit('approveWithdrawal', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }

        function rejectWithdrawal(id) {
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
                            Livewire.emit('rejectWithdrawal', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }


    </script>
@endpush
