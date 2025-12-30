@extends('layouts.app')
@section('title', 'Connected Projects | DecentraX Admin')
@section('ogtitle', 'Connected Projects | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Connected Projects</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Connected Projects</li>
            </ol>
        </nav>


        <div class="row">
            <div class="col-12 text-right">
                <a class="btn btn-secondary" href="{{ route('connected-projects.new') }}"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>
        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
                <livewire:connected-projects.connected-projects-data-table />
            </div>

        </div>

    </div>
@endsection
@push('scripts')
    <script>
function publishProject(id) {
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
                            Livewire.emit('publishProject', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }

        function unpublishProject(id) {

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
                            Livewire.emit('unpublishProject', id)

                        }
                    },
                    cancel: function() {}
                }
            });
        }
    </script>
@endpush
