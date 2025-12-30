<div class="dropleft no-arrow mb-1">
    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fas fa-cog"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
        @if ($status == App\Models\UrbxWalletRedeem::STATUS['WITHDRAWAL_PENDING'])
            <a class="dropdown-item edit-product" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="approveURBXWithdrawal('{{ $id }}')">
                <i class="fas fa-check-double"></i> &nbsp;Approve
            </a>
            <a class="dropdown-item edit-product" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="rejectURBXWithdrawal('{{ $id }}')">
                <i class="fas fa-times-circle"></i> &nbsp;Reject
            </a>
        {{-- @elseif ($status == App\Models\WalletRedeem::STATUS['APPROVED'])
            <a class="dropdown-item edit-product" href="javascript:void(0)" class="btn btn-warning" title=""
                onclick="rejectURBXWithdrawal('{{ $id }}')">
                <i class="fas fa-times-circle"></i> &nbsp;Reject
             </a> --}}
        @endif


    </div>
</div>
