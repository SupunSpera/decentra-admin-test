<div>
    <form wire:submit.prevent="submit" onsubmit="disableSubmitButton()">
        <div class="form-group row">
            <label for="inp_first_name" class="col-sm-2 col-form-label">First Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                    id="inp_first_name" placeholder="Enter First Name" wire:model="first_name">
                @if ($errors->has('first_name'))
                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_last_name" class="col-sm-2 col-form-label">Last Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                    id="inp_last_name" placeholder="Enter Last Name" wire:model="last_name">
                @if ($errors->has('last_name'))
                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }} "
                    id="inp_email" wire:model="email" placeholder="Enter Email">
                @if ($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif

            </div>

        </div>
        <div class="form-group row">
            <label for="referral_id" class="col-sm-2 col-form-label">Direct Referral</label>
            <div class="col-sm-10">
                <select class="form-control @error('referral_id') is-invalid @enderror" name="referral_id"
                    wire:model="referral_id" id="referral_id">
                    <option value="">Select Referral</option>
                    @foreach ($referrals as $referral)
                        <option value="{{ $referral['id'] }}">{{ $referral->customer['email'] }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('referral_id'))
                    <div class="invalid-feedback">{{ $errors->first('referral_id') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="parentReferral" class="col-sm-2 col-form-label">Parent Referral</label>
            <div class="col-sm-10">
                <select class="form-control @error('parentReferral') is-invalid @enderror" name="parentReferral"
                    wire:model="parentReferral" id="parentReferral" wire:change="getParentPlacement">
                    <option value="0">Default</option>
                    @foreach ($referrals as $referral)
                        <option value="{{ $referral['id'] }}">{{ $referral->customer['email'] }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('parentReferral'))
                    <div class="invalid-feedback">{{ $errors->first('parentReferral') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="placement" class="col-sm-2 col-form-label">Placement</label>
            <div class="col-sm-10">
                <select class="form-control @error('placement') is-invalid @enderror" name="placement"
                    wire:model="placement" id="placement">
                    <option value="0">Default</option>
                    @if (count($ParentChildren) > 0)
                        @foreach ($ParentChildren as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach

                    @endif

                </select>
                @if ($errors->has('placement'))
                    <div class="invalid-feedback">{{ $errors->first('placement') }}</div>
                @endif
            </div>
        </div>

        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark" wire:click="clearForm">Clear</button>
            <button id="submit-btn" type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>

    <script>
        function disableSubmitButton() {
            document.getElementById("submit-btn").disabled = true;
            document.getElementById("submit-btn").innerText = "Processing...";
        }
    </script>
</div>
