<div class="mb-1 dropleft no-arrow">
    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-cog"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
        <a class="dropdown-item edit-product" href="{{ route('institutes.view', $id) }}" class="btn btn-warning"
            title="View">
            <i class="fas fa-eye"></i> &nbsp;View
        </a>
        @if ($status == 0)
            <a class="dropdown-item edit-product" href="javascript:void(0)" class="btn btn-warning" title="Approve"
                data-toggle="modal" data-target="#approveInstituteModal_{{ $id }}">
                <i class="fas fa-check-double"></i> &nbsp;Approve
            </a>
        @endif
    </div>
</div>
<div class="modal fade" id="approveInstituteModal_{{ $id }}" tabindex="-1" role="dialog"
    aria-labelledby="approveInstituteModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveInstituteModalLabel">Approve Institute</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="frozen_asset">Frozen Asset</label>
                    <input type="number" id="frozen_asset_{{ $id }}" class="form-control" wire:model.defer="frozen_asset" placeholder="Please enter frozen asset count" required />
                    <span id="frozen_asset_error_{{ $id }}" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"
                    onclick="validateAndApprove('{{ $id }}')">Approve</button>
            </div>
        </div>
    </div>
</div>
