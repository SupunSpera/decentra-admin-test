@php
    $curr_url = Route::currentRouteName();
@endphp
<div class="row  mb-4">
    <div class="col-12">

        <ul class="nav nav-pills nav-fill" role="tablist">
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['customers.edit']) ? 'active' : '' }}
                "
                    href="{{ route('customers.edit',$id)}}">
                    <span class="d-block d-md-none"><i class="fa fa-id-card"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-user"></i> Profile
                    </span>
                </a>
            </li>
            &nbsp;&nbsp;
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['customers.wallet']) ? 'active' : '' }}
                "
                    href="{{ route('customers.wallet', $id) }}">
                    <span class="d-block d-md-none"><i class="fa fa-wallet"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-wallet"></i> Wallet
                    </span>
                </a>
            </li>
            &nbsp;&nbsp;
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded  {{ in_array($curr_url, ['customers.referrals']) ? 'active' : '' }}"
                    href="{{ route('customers.referrals', $id) }}">
                    <span class="d-block d-md-none"><i class="fa fa-users"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-users"></i> Referrals
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>
