@php
    $curr_url = Route::currentRouteName();
@endphp
<div class="row  mb-4">
    <div class="col-12">

        <ul class="nav nav-pills nav-fill" role="tablist">
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['institutes.view']) ? 'active' : '' }}
                "
                    href="{{ route('institutes.view',$institute->id)}}">
                    <span class="d-block d-md-none"><i class="fa fa-id-card"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-user"></i> Profile
                    </span>
                </a>
            </li>
            &nbsp;&nbsp;
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['institutes.wallet']) ? 'active' : '' }}
                "
                    href="{{ route('institutes.wallet', $institute->id) }}">
                    <span class="d-block d-md-none"><i class="fa fa-wallet"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-wallet"></i> Wallet
                    </span>
                </a>
            </li>
            &nbsp;&nbsp;
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['institutes.ref-members']) ? 'active' : '' }}
                "
                    href="{{ route('institutes.ref-members', $institute->id) }}">
                    <span class="d-block d-md-none"><i class="fa fa-users"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-users"></i> Members
                    </span>
                </a>
            </li>

        </ul>
    </div>
</div>
