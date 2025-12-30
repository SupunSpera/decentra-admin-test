<div class="mb-1 dropleft no-arrow">
    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-cog"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
        {{-- <a class="dropdown-item edit-item" href="{{ route('items.view', $id) }}" class="btn btn-warning"
            title="">
            <i class="fa fa-eye"></i>&nbsp;View
        </a> --}}
        <a class="dropdown-item edit-item" href="{{ route('items.edit', $id) }}" class="btn btn-warning"
            title="">
            <i class="fas fa-edit"></i>&nbsp;Edit
        </a>

        @if ($status == App\Models\Item::STATUS['DRAFT'])
            <a class="dropdown-item edit-item" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="publishitem('{{ $id }}')">
                <i class="fas fa-check-double"></i> &nbsp;Publish
            </a>
        @endif
        @if ($status == App\Models\Item::STATUS['PUBLISHED'])
            <a class="dropdown-item edit-item" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="unpublishitem('{{ $id }}')">
                <i class="fas fa-times-circle"></i> &nbsp;Unpublish
            </a>
        @endif

        {{-- <a class="dropdown-item delete-item" href="javascript:void(0)" class="btn btn-danger" title=""
            onclick="deleteitem('{{ $id }}')"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</a> --}}
    </div>
</div>
