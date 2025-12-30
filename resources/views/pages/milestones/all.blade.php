@extends('layouts.app')
@section('title', 'Milestones | DecentraX Admin')
@section('ogtitle', 'Milestones | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Milestones</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Milestone</li>
            </ol>
        </nav>

        <!-- Content Row -->

        <div class="row">
            <div class="col-12 text-right">
                <a class="btn btn-secondary" href="{{ route('milestones.new') }}"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <livewire:milestone.milestone-data-table />
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    {{-- <script>
        function publishProduct(id) {
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
                            Livewire.emit('publishProduct', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }

        function unpublishProduct(id) {

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
                            Livewire.emit('unpublishProduct', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }


        function deleteProduct(id) {
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
                            Livewire.emit('deleteRecord', id)

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
    </script> --}}
    <script>
        function deleteMilestone(id) {
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
                            Livewire.emit('deleteRecord', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }
    </script>
@endpush
