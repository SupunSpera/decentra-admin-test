<div>
    <form wire:submit.prevent="submit" onsubmit="setDescription()">
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
                                            id="inp_name" placeholder="Enter Product Name" wire:model="name">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <label for="inp_description" class="col col-form-label">Description</label>
                                <div class="form-group " wire:ignore>
                                    <div class="col-sm-10">
                                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="" id="inp_description"
                                            cols="30" rows="5" wire:model="description"></textarea>
                                        @if ($errors->has('description'))
                                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="payment_type" class="col col-form-label">Payment Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control @error('payment_type') is-invalid @enderror"
                                            name="payment_type" wire:model="payment_type" id="payment_type">
                                            <option value="0">One Time </option>
                                            <option value="1">Monthly </option>

                                        </select>
                                        @if ($errors->has('payment_type'))
                                            <div class="invalid-feedback">{{ $errors->first('payment_type') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Price</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                                            id="inp_price" placeholder="Enter Product Price" wire:model="price">
                                        @if ($errors->has('price'))
                                            <div class="invalid-feedback">{{ $errors->first('price') }}</div>
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
                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Points</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('points') ? 'is-invalid' : '' }}"
                                            id="inp_price" placeholder="Enter Product Points" wire:model="points">
                                        @if ($errors->has('points'))
                                            <div class="invalid-feedback">{{ $errors->first('points') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inp_price" class="col col-form-label">Level</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control {{ $errors->has('level') ? 'is-invalid' : '' }}"
                                            min="0" id="inp_price" placeholder="Enter Product Level"
                                            wire:model="level">
                                        @if ($errors->has('level'))
                                            <div class="invalid-feedback">{{ $errors->first('level') }}</div>
                                        @endif
                                    </div>
                                </div>
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
                                @elseif ($productImage)
                                    <img src="{{ env('APP_URL') . '/storage/uploads/images/products/' . $productImage }}"
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
    @push('styles')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">

    @endpush
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            // let isFocused = false;

            // Initialize Summernote
            $('#inp_description').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ],
                callbacks: {
                    // onFocus: function() {
                    //     isFocused = true; // Track focus state
                    // },
                    // onBlur: function() {
                    //     isFocused = false; // Reset focus state
                    //     setDescription(); // Update description on blur
                    // },
                    onChange: function(contents) {
                        // clearTimeout(debounceTimer); // Clear previous timer
                        // debounceTimer = setTimeout(() => {
                            @this.set('description', contents); // Sync with Livewire model
                        // }, 300); // Wait 300ms after last change
                    }
                }
            });


            // Reinitialize Summernote after Livewire updates if not focused
            document.addEventListener('livewire:update', function () {
                if (!isFocused) { // Only update if Summernote is not focused
                    let content = @this.get('description');
                    $('#inp_description').summernote('code', content);
                }
            });
        });

        function setDescription() {
            let description = $('#inp_description').summernote('code');
            @this.set('description', description);
        }
    </script>

    @endpush
</div>
