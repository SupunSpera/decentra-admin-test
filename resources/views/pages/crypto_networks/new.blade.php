@extends('layouts.app')
@section('title', 'New Crypto Network | DecentraX Admin')
@section('ogtitle', 'New Crypto Network | DecentraX Admin')
@section('header')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Crypto Network Management</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('crypto-networks.all') }}">Crypto Networks</a></li>
                <li class="breadcrumb-item active" aria-current="page">New</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl-8 col-lg-10">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Create Crypto Network</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('crypto-networks.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="chain_id">Chain ID</label>
                                <input type="text" name="chain_id" id="chain_id" class="form-control"
                                    value="{{ old('chain_id') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="rpc_http">RPC HTTP URL</label>
                                <input type="url" name="rpc_http" id="rpc_http" class="form-control"
                                    value="{{ old('rpc_http') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="rpc_ws">RPC WebSocket URL</label>
                                <input type="url" name="rpc_ws" id="rpc_ws" class="form-control"
                                    value="{{ old('rpc_ws') }}">
                            </div>

                            <div class="form-group">
                                <label for="tokens">Tokens (JSON)</label>
                                <textarea name="tokens" id="tokens" rows="5" class="form-control"
                                    placeholder='{"USDT": "0x...", "TOKEN2": "0x..."}'>{{ old('tokens') }}</textarea>
                                <small class="form-text text-muted">Provide a JSON object of symbol → address, e.g.
                                    {"USDT":"0x...","BTE":"0x..."}</small>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <div class="form-group text-right">
                                <a href="{{ route('crypto-networks.all') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection










