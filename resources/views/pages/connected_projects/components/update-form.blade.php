<div>
    <form wire:submit.prevent="submit" onsubmit="setProjectDescription()">
        <div class="form-group row">
            <label for="inp_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    id="inp_name" placeholder="Enter Project Name" wire:model="name">
                @if ($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_url" class="col-sm-2 col-form-label">Public URL</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('public_url') ? 'is-invalid' : '' }}"
                    id="inp_public_url" placeholder="Enter Public URL" wire:model="public_url">
                @if ($errors->has('public_url'))
                    <div class="invalid-feedback">{{ $errors->first('public_url') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="inp_url" class="col-sm-2 col-form-label">Admin URL</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('admin_url') ? 'is-invalid' : '' }}"
                    id="inp_admin_url" placeholder="Enter Admin URL" wire:model="admin_url">
                @if ($errors->has('admin_url'))
                    <div class="invalid-feedback">{{ $errors->first('admin_url') }}</div>
                @endif
            </div>
        </div>

        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark">Clear</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>


</div>
