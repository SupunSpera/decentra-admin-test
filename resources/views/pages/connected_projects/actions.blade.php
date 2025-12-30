<div class="dropleft no-arrow mb-1">
    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-cog"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
        {{-- <a class="dropdown-item edit-product" href="{{ route('projects.view', $id) }}" class="btn btn-warning"
            title="">
            <i class="fa fa-eye"></i>&nbsp;View
        </a> --}}
        <a class="dropdown-item edit-product" href="{{ route('connected-projects.edit', $id) }}" class="btn btn-warning"
            title="">
            <i class="fas fa-edit"></i>&nbsp;Edit
        </a>

        @if ($status == App\Models\ConnectedProject::STATUS['DRAFT'])
            <a class="dropdown-item edit-product" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="publishProject('{{ $id }}')">
                <i class="fas fa-check-double"></i> &nbsp;Publish
            </a>
        @endif
        @if ($status == App\Models\ConnectedProject::STATUS['PUBLISHED'])
            <a class="dropdown-item edit-product" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="unpublishProject('{{ $id }}')">
                <i class="fas fa-times-circle"></i> &nbsp;Unpublish
            </a>
        @endif

    </div>
</div>
