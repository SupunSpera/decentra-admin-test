<div>
    <form wire:submit.prevent="submit" onsubmit="setProjectDescription()">
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
                                            id="inp_name" placeholder="Enter Project Name" wire:model="name">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group " wire:ignore>
                                    <label for="inp_description" class="col col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="inp_project_description"
                                            cols="30" rows="5" wire:model="description"></textarea>
                                        @if ($errors->has('description'))
                                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="project_type" class="col col-form-label">Product Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control @error('type') is-invalid @enderror"
                                            wire:model="type" wire:model="type" id="project_type">
                                            <option value="0" {{ $project->type == 0 ? 'selected' : '' }}>Short Term
                                            </option>
                                            <option value="1" {{ $project->type == 1 ? 'selected' : '' }}>Long Term
                                            </option>

                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Total Value</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('total_value') ? 'is-invalid' : '' }}"
                                            id="inp_price" placeholder="Enter Project Price" wire:model="total_value">
                                        @if ($errors->has('total_value'))
                                            <div class="invalid-feedback">{{ $errors->first('total_value') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Minimum Investment</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('minimum_investment') ? 'is-invalid' : '' }}"
                                            id="inp_price" placeholder="Enter Project Price" wire:model="minimum_investment">
                                        @if ($errors->has('minimum_investment'))
                                            <div class="invalid-feedback">{{ $errors->first('minimum_investment') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="inp_price" class="col col-form-label">Points </label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('points') ? 'is-invalid' : '' }}"
                                            id="inp_price" placeholder="Enter Points Amount"
                                            wire:model="points">
                                        @if ($errors->has('points'))
                                            <div class="invalid-feedback">{{ $errors->first('points') }}
                                            </div>
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
                                    <label for="duration" class="col col-form-label">Duration</label>
                                    <div class="col-sm-10">

                                        <div class="input-group is-invalid">
                                            <input type="number"
                                                class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}"
                                                placeholder="Enter value" min="1" id="duration"
                                                wire:model="duration">
                                            <div class="input-group-append">
                                                <select class="custom-select" id="duration-type"
                                                    wire:model="duration_type">
                                                    <option value="0">Months</option>
                                                    <option value="1">Years</option>
                                                </select>
                                            </div>
                                        </div>

                                        @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="inp_harvest_amount" class="col col-form-label">Harvest (%)</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('harvest') ? 'is-invalid' : '' }}"
                                            id="inp_harvest_amount" placeholder="Enter Harvest Amount" wire:model="harvest">
                                        @if ($errors->has('harvest'))
                                            <div class="invalid-feedback">{{ $errors->first('harvest') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="project_type" class="col col-form-label">Harvest Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control @error('harvest_type') is-invalid @enderror"
                                            wire:model="harvest_type" wire:model="harvest_type" id="project_type">
                                            <option value="0" {{ $project->harvest_type == 0 ? 'selected' : '' }}>Monthly
                                            </option>
                                            <option value="1" {{ $project->harvest_type == 1 ? 'selected' : '' }}>On Complete
                                            </option>

                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="inp_direct_commission" class="col col-form-label">Direct Referral Commission (%)</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            class="form-control {{ $errors->has('direct_commission') ? 'is-invalid' : '' }}"
                                            id="inp_direct_commission" placeholder="Enter Direct Referral Commission"
                                            wire:model="direct_commission">
                                        @if ($errors->has('direct_commission'))
                                            <div class="invalid-feedback">{{ $errors->first('direct_commission') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="project_type" class="col col-form-label">Bonus Generation</label>
                                    <div class="col-sm-10">
                                        <select class="form-control @error('bonus_generation') is-invalid @enderror"
                                            wire:model="bonus_generation" wire:model="bonus_generation" id="project_type">
                                            <option value="0" {{ $project->bonus_generation == 0 ? 'selected' : '' }}>Early
                                            </option>
                                            <option value="1" {{ $project->bonus_generation == 1 ? 'selected' : '' }}>On Complete
                                            </option>

                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
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
                                @elseif ($projectImage)
                                    <img src="{{ env('APP_URL') . '/storage/uploads/images/projects/' . $projectImage }}"
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
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                // Initialize Summernote
                $('#inp_project_description').summernote({
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
                        onChange: function(contents) {
                            @this.set('description', contents); // Sync with Livewire model
                        }
                    }
                });

                // // Sync the description field before each Livewire update
                // window.addEventListener('beforeLivewireUpdate', () => {
                //     let description = $('#inp_description').summernote('code');
                //     @this.set('description', description);
                // });

                // Reinitialize Summernote after Livewire updates if not focused
                document.addEventListener('livewire:update', function() {
                    if (!isFocused) { // Only update if Summernote is not focused
                        let projectContent = @this.get('description');
                        $('#inp_project_description').summernote('code', projectContent);
                    }
                });
            });

            function setProjectDescription() {
                let projectDescription = $('#inp_project_description').summernote('code');
                @this.set('description', projectDescription);
            }
        </script>
    @endpush

</div>
