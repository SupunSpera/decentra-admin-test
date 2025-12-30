<div>
    <form wire:submit.prevent="submit">
        <div class="row justify-content-center">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                {{-- <div class="form-group row">
                                    <label for="daily_income_cap" class="col col-form-label">Daily Income Cap</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('daily_income_cap') ? 'is-invalid' : '' }}"
                                            id="inp_daily_income_cap" placeholder="Enter Daily Income Cap"
                                            wire:model="daily_income_cap">
                                        @error('daily_income_cap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <label for="inp_tex_amount" class="col col-form-label">Maximum URBX Supply</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('inp_tex_amount') ? 'is-invalid' : '' }}"
                                            id="inp_tex_amount" placeholder="Enter Maximum URBX Supply"
                                            wire:model="inp_tex_amount">

                                        @error('inp_tex_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inp_share_value" class="col col-form-label">Point Value</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('inp_share_value') ? 'is-invalid' : '' }}"
                                            id="inp_share_value" placeholder="Enter Share Value"
                                            wire:model="inp_share_value">

                                        @error('inp_share_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="usd_to_bte_fee" class="col col-form-label">USDT To URBX Swap Fee
                                        (%)</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('usd_to_bte_fee') ? 'is-invalid' : '' }}"
                                            id="usd_to_bte_fee" placeholder="Enter USDT To URBX Swap Fee"
                                            wire:model="usd_to_bte_fee">

                                        @error('usd_to_bte_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bte_to_usd_fee" class="col col-form-label">URBX To USDT Swap Fee
                                        (%)</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('bte_to_usd_fee') ? 'is-invalid' : '' }}"
                                            id="bte_to_usd_fee" placeholder="Enter URBX To USDT Swap Fee"
                                            wire:model="bte_to_usd_fee">

                                        @error('bte_to_usd_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="withdrawal_fee" class="col col-form-label">Withdrawal Fee (%)</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('withdrawal_fee') ? 'is-invalid' : '' }}"
                                            id="withdrawal_fee" placeholder="Enter Withdrawal Fee"
                                            wire:model="withdrawal_fee">

                                        @error('withdrawal_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="unhold_date" class="col col-form-label">Unhold Period</label>
                                    <div class="col-sm-10">
                                        {{-- <input type="date"
                                            class="form-control {{ $errors->has('unhold_date') ? 'is-invalid' : '' }}"
                                            id="unhold_date" placeholder="Enter Unhold Date" min="{{ now()->format('Y-m-d') }}"
                                            wire:model="unhold_date"> --}}
                                        <div class="input-group is-invalid">
                                            <input type="number" class="form-control {{ $errors->has('unhold_period') ? 'is-invalid' : '' }}" placeholder="Enter value"
                                                min="1" id="duration-value" wire:model="unhold_period">
                                            <div class="input-group-append">
                                                <select class="custom-select" id="unhold-duration-type"
                                                    wire:model="unhold_period_type">
                                                    <option value="1">Days</option>
                                                    <option value="7">Weeks</option>
                                                    <option value="30">Months</option>
                                                </select>
                                            </div>
                                        </div>

                                        @error('unhold_period')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="unfreeze_date" class="col col-form-label">Unfreeze Period</label>
                                    <div class="col-sm-10">
                                        {{-- <input type="date"
                                            class="form-control {{ $errors->has('unfreeze_date') ? 'is-invalid' : '' }}"
                                            id="unfreeze_date" placeholder="Enter Unfreeze Date" min="{{ now()->format('Y-m-d') }}"
                                            wire:model="unfreeze_date"> --}}
                                        <div class="input-group is-invalid">
                                            <input type="number" class="form-control {{ $errors->has('unfreeze_period') ? 'is-invalid' : '' }}" placeholder="Enter value"
                                                min="1" id="duration-value" wire:model="unfreeze_period">
                                            <div class="input-group-append">
                                                <select class="custom-select" id="unfreeze-duration-type"
                                                    wire:model="unfreeze_period_type">
                                                    <option value="1">Days</option>
                                                    <option value="7">Weeks</option>
                                                    <option value="30">Months</option>
                                                </select>
                                            </div>
                                        </div>

                                        @error('unfreeze_period')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 mt-2 text-center">

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </form>

    <script></script>
</div>
