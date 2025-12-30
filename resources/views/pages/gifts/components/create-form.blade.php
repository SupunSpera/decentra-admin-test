<div>
    <form wire:submit.prevent="submit">
        <div class="form-group row">
            <label for="inp_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    id="inp_name" placeholder="Enter Gift Name" wire:model="name">
                @if ($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                    name="" id="inp_description" cols="30" rows="5" wire:model="description"></textarea>
                @if ($errors->has('description'))
                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                @endif
            </div>
        </div>


        <div class="form-group row">
            <label for="inp_price" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('token_amount') ? 'is-invalid' : '' }}"
                    id="inp_price" placeholder="Enter Token Amount" wire:model="token_amount">
                @if ($errors->has('token_amount'))
                    <div class="invalid-feedback">{{ $errors->first('token_amount') }}</div>
                @endif
            </div>
        </div>
        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark">Clear</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>

    <script></script>
</div>
