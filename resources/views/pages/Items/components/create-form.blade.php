<div>
    <form wire:submit.prevent="submit">
        <div class="form-group row">
            <label for="inp_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="inp_name"
                    placeholder="Enter Item Name" wire:model="name">
                @if ($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="" id="inp_description"
                    cols="30" rows="5" wire:model="description"></textarea>
                @if ($errors->has('description'))
                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="payment_type" class="col-sm-2 col-form-label">Item Type</label>
            <div class="col-sm-10">
                <select class="form-control @error('payment_type') is-invalid @enderror" name="payment_type"
                    wire:model="payment_type" id="payment_type">
                    <option value="">Payment Type</option>
                    <option value="0">One Time </option>
                    <option value="1">Monthly </option>

                </select>
                @if ($errors->has('payment_type'))
                    <div class="invalid-feedback">{{ $errors->first('payment_type') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_price" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                    id="inp_price" placeholder="Enter Item Price" wire:model="price">
                @if ($errors->has('price'))
                    <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_points" class="col-sm-2 col-form-label">Points</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('points') ? 'is-invalid' : '' }}"
                    id="inp_points" placeholder="Enter Item Points" wire:model="points">
                @if ($errors->has('points'))
                    <div class="invalid-feedback">{{ $errors->first('points') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_price" class="col-sm-2 col-form-label">Level</label>
            <div class="col-sm-10">
                <input type="number" class="form-control {{ $errors->has('level') ? 'is-invalid' : '' }}"
                    id="inp_level" placeholder="Enter Item Level" wire:model="level" min="1">
                @if ($errors->has('level'))
                    <div class="invalid-feedback">{{ $errors->first('level') }}</div>
                @endif
            </div>
        </div>
        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark ">Clear</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>

    <script></script>
</div>
