<div>
    <form wire:submit.prevent="submit">
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
            <label for="referral_id" class="col-sm-2 col-form-label">Referral</label>
            <div class="col-sm-10">
                <select class="form-control @error('referral_id') is-invalid @enderror" disabled
                    name="referral_id" wire:model="referral_id" id="referral_id" >
                    <option value="0">Select Referral</option>

                    @foreach ($referrals as $referral)
                        <option value="{{ $referral['id'] }}">{{ $referral->customer['email']}}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('referral_id'))
                    <div class="invalid-feedback">{{ $errors->first('referral_id') }}</div>
                @endif
            </div>
        </div>
        {{-- <div class="form-group row">
            <label for="inp_mobile" class="col-sm-2 col-form-label">Mobile</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                    id="inp_mobile" placeholder="Enter Mobile Number" wire:model="mobile">
                @if ($errors->has('mobile'))
                    <div class="invalid-feedback">{{ $errors->first('mobile') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    id="inp_password" placeholder="Enter Password" wire:model="password">
                @if ($errors->has('password'))
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control {{ $errors->has('confirmPassword') ? 'is-invalid' : '' }}"
                    id="inp_confirm_password" placeholder="Enter Confirm Password" wire:model="confirmPassword">
                @if ($errors->has('confirmPassword'))
                    <div class="invalid-feedback">{{ $errors->first('confirmPassword') }}</div>
                @endif

            </div>
        </div> --}}
        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark">Clear</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>

    <script></script>
</div>
