<div>
    <form wire:submit.prevent="submit">
        <div class="row justify-content-center">
            <div class="col-lg-6 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group ">
                                    <label for="inp_name" class="col col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                            id="inp_name" placeholder="Enter Title" wire:model="title">
                                        @if ($errors->has('title'))
                                            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
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
                                    <label for="inp_price" class="col col-form-label">Delivery Date</label>
                                    <div class="col-sm-10">
                                        <input type="date"
                                            class="form-control {{ $errors->has('deliver_date') ? 'is-invalid' : '' }}"
                                            id="inp_price" placeholder="Enter Delivery Date" wire:model="deliver_date">
                                        @if ($errors->has('deliver_date'))
                                            <div class="invalid-feedback">{{ $errors->first('deliver_date') }}</div>
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
                                    <label for="images"><b>Images <sup class="text-danger">*</sup></b></label>
                                    <div class="col-sm-10">
                                        <input type="file"
                                            class="form-control @error('images.*') is-invalid @enderror"
                                            wire:model="images" multiple>
                                        @error('images')
                                            <div class="invalid-feedback d-block">{{ $errors->first('images') }}</div>
                                        @enderror
                                        @error('images.*')
                                            <div class="invalid-feedback d-block">{{ $errors->first('images') }}</div>
                                        @enderror

                                    </div>

                                </div>

                                @if ($images)
                                    @foreach ($images as $image)
                                        <img src="{{ $image->temporaryUrl() }}" alt="Uploaded Image" width="100">
                                    @endforeach
                                @endif

                                @if ($projectUpdateImages)

                                    <div class="row">

                                        @foreach ($projectUpdateImages as $projectImage)
                                            <div class="col-4">
                                                <button type="button" class="btn btn-danger btn-sm mt-2"
                                                    onclick="confirmDelete({{ $projectImage->id }})"
                                                    style="position: relative;top: 9%;left: 55%;">
                                                    X
                                                </button>
                                                <img src="{{ env('APP_URL') . '/storage/uploads/images/projects/updates/' . $projectImage->image->name }}"
                                                    alt="Image" width="100">
                                            </div>
                                        @endforeach
                                    </div>
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

    <script>
        function confirmDelete(imageId) {
            $.confirm({
                title: 'Confirm Deletion',
                content: 'Are you sure you want to delete this image?',
                buttons: {
                    confirm: function() {
                        // Trigger the Livewire event to delete the image
                        Livewire.emit('deleteImage', imageId);
                    },
                    cancel: function() {
                        // Do nothing if cancelled
                    }
                }
            });
        }
    </script>
</div>
