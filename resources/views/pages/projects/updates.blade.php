@extends('layouts.app')
@section('title', 'Project | DecentraX Admin')
@section('ogtitle', 'Project | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Project Management</h1>

        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.all') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Updates</li>
            </ol>
        </nav>
        @include('pages.projects.components.nav')
        <!-- Content Row -->
        <div class="row">
            <div class="col-12 text-right">
                <a class="btn btn-secondary" href="{{ route('projects.updates.new',['id' => $id]) }}"><i class="fa fa-plus"></i> Add New</a>


            </div>
        </div>
        <div class="row ">
            <div class="col-xl-12 col-lg-12">

                <livewire:project.project-updates-data-table  :projectId="$id" />
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


        function deleteProject(id) {
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
                content: 'Something went wrong!',
                autoClose: 'cancel|8000',
                type: 'red',
            });

        })
        </script>
    @endpush
