<div>
    <form wire:submit.prevent="submit">
        <div class="form-group row">
            <label for="inp_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="inp_name"
                    placeholder="Enter Milestone Name" wire:model="name">
                @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="payment_type" class="col-sm-2 col-form-label">Milestone Type</label>
            <div class="col-sm-10">
                <select class="form-control @error('type') is-invalid @enderror" name="payment_type" wire:model="type"
                    id="type">
                    <option value="">Type</option>
                    <option value="0">Direct Referrals </option>
                    <option value="1">Client Base </option>

                </select>
                @if ($errors->has('type'))
                <div class="invalid-feedback">{{ $errors->first('payment_type') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_price" class="col-sm-2 col-form-label">Count</label>
            <div class="col-sm-10">
                <input type="number" class="form-control {{ $errors->has('count') ? 'is-invalid' : '' }}" id="inp_count"
                    placeholder="Enter Count" wire:model="count" min="1">
                @if ($errors->has('count'))
                <div class="invalid-feedback">{{ $errors->first('count') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_points" class="col-sm-2 col-form-label">Level</label>
            <div class="col-sm-10">
                <input type="number" class="form-control {{ $errors->has('level') ? 'is-invalid' : '' }}" id="inp_level"
                    placeholder="Enter Milestone Level" wire:model="level" min="1">
                @if ($errors->has('level'))
                <div class="invalid-feedback">{{ $errors->first('level') }}</div>
                @endif
            </div>
        </div>
        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark " wire:click="resetForm">Clear</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
