@php
    $curr_url = Route::currentRouteName();
@endphp
<div class="row  mb-4">
    <div class="col-12">

        <ul class="nav nav-pills nav-fill" role="tablist">
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['products.edit']) ? 'active' : '' }}
                "
                    href="{{ route('products.edit',$id)}}">
                    <span class="d-block d-md-none"><i class="fa fa-id-card"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fas fa-edit"></i> Basic
                    </span>
                </a>
            </li>
            &nbsp;&nbsp;
            <li class="nav-item waves-effect waves-light  mb-2 mt-2 second-nav">
                <a class="nav-link  border border-primary rounded
                {{ in_array($curr_url, ['products.terms']) ? 'active' : '' }}
                "
                    href="{{ route('products.terms', $id) }}">
                    <span class="d-block d-md-none"><i class="fa fa-file"></i></span>
                    <span class="d-none d-md-block">
                        <i class="fa fa-file"></i> Terms
                    </span>
                </a>
            </li>
            &nbsp;&nbsp;

        </ul>
    </div>
</div>
