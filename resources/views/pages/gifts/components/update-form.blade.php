<div>
    <form wire:submit.prevent="submit">
        <div class="row justify-content-center">
            <div class="col-lg-6 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group ">
                                    <label for="inp_name" class="col col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                            id="inp_name" placeholder="Enter Gift Name" wire:model="name">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="inp_description" class="col col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="" id="inp_description"
                                            cols="30" rows="5" wire:model="description"></textarea>
                                        @if ($errors->has('description'))
                                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Token Amount</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('token_amount') ? 'is-invalid' : '' }}"
                                            id="inp_token_amount" placeholder="Enter Product Price" wire:model="token_amount">
                                        @if ($errors->has('token_amount'))
                                            <div class="invalid-feedback">{{ $errors->first('token_amount') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="inp_name"><b>Image <sup class="text-danger">*</sup></b> </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        wire:model="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- {{dump($giftImage)}} --}}
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt="Uploaded Image">
                                @elseif ($giftImage)
                                    <img src="{{ env('APP_URL').'/storage/uploads/images/gifts/' . $giftImage }}"
                                        alt="Image" width="500" height="300">
                                    {{-- config('app.image_path') . --}}
                                @endif
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
