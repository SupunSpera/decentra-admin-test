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
                                            id="inp_name" placeholder="Enter Milestone Name" wire:model="name">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="payment_type" class="col col-form-label">Milestone Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control @error('type') is-invalid @enderror"
                                            name="type" wire:model="type" id="type">
                                            <option value="">Type</option>
                                            <option value="0">Direct Referrals </option>
                                            <option value="1">Client Base </option>

                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Count</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control {{ $errors->has('count') ? 'is-invalid' : '' }}"
                                            id="inp_count" placeholder="Enter Count" wire:model="count" min="1">
                                        @if ($errors->has('count'))
                                            <div class="invalid-feedback">{{ $errors->first('count') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inp_price" class="col col-form-label">Level</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control {{ $errors->has('level') ? 'is-invalid' : '' }}"
                                            min="1" id="inp_price" placeholder="Enter Product Level"
                                            wire:model="level">
                                        @if ($errors->has('level'))
                                            <div class="invalid-feedback">{{ $errors->first('level') }}</div>
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
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            wire:model="image">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt="Uploaded Image">
                                @elseif ($milestoneImage)
                                    <img src="{{ env('APP_URL') . '/storage/uploads/images/milestones/' . $milestoneImage }}"
                                        alt="Image" width="500" height="300">
                                    {{-- config('app.image_path') . --}}
                                @endif
                                <div class="mt-2 text-center col-12">

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
