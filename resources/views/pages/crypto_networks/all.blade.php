@extends('layouts.app')
@section('title', 'Crypto Networks | DecentraX Admin')
@section('ogtitle', 'Crypto Networks | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Crypto Networks</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crypto Networks</li>
            </ol>
        </nav>

        <div class="row mb-3">
            <div class="col-12 text-right">
                <a class="btn btn-secondary" href="{{ route('crypto-networks.new') }}"><i class="fa fa-plus"></i> Add New
                    Network</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Networks</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Chain ID</th>
                                    <th>RPC HTTP</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($networks as $network)
                                    <tr>
                                        <td>{{ $network->id }}</td>
                                        <td>{{ $network->name }}</td>
                                        <td>{{ $network->chain_id }}</td>
                                        <td>{{ $network->rpc_http }}</td>
                                        <td>
                                            @if ($network->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('crypto-networks.view', $network->id) }}"
                                                class="btn btn-sm btn-primary">View</a>
                                            <a href="{{ route('crypto-networks.edit', $network->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No networks found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection










