@extends('layouts.app')
@section('title', 'View Crypto Network | DecentraX Admin')
@section('ogtitle', 'View Crypto Network | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Crypto Network</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('crypto-networks.all') }}">Crypto Networks</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl-8 col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Network Details</h6>
                        <a href="{{ route('crypto-networks.edit', $id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Name</dt>
                            <dd class="col-sm-9">{{ $network->name ?? '' }}</dd>

                            <dt class="col-sm-3">Chain ID</dt>
                            <dd class="col-sm-9">{{ $network->chain_id ?? '' }}</dd>

                            <dt class="col-sm-3">RPC HTTP</dt>
                            <dd class="col-sm-9">{{ $network->rpc_http ?? '' }}</dd>

                            <dt class="col-sm-3">RPC WS</dt>
                            <dd class="col-sm-9">{{ $network->rpc_ws ?? '' }}</dd>

                            <dt class="col-sm-3">Active</dt>
                            <dd class="col-sm-9">
                                @if (!empty($network) && $network->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Tokens</dt>
                            <dd class="col-sm-9">
                                @if (!empty($network) && is_array($network->tokens))
                                    <pre class="mb-0">{{ json_encode($network->tokens, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-muted">No tokens configured</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection










