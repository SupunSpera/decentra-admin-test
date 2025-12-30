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
        <div class="form-group row" wire:ignore>
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
            <label for="project_type" class="col-sm-2 col-form-label">Product Type</label>
            <div class="col-sm-10">
                <select class="form-control @error('type') is-invalid @enderror" wire:model="type"
                    wire:model="type" id="project_type">
                    <option value="">Project Type </option>
                    <option value="0">Short Term </option>
                    <option value="1">Long Term</option>

                </select>
                @if ($errors->has('type'))
                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inp_price" class="col-sm-2 col-form-label">Total Value</label>
            <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('total_value') ? 'is-invalid' : '' }}"
                    id="inp_price" placeholder="Enter Product Price" wire:model="total_value">
                @if ($errors->has('total_value'))
                    <div class="invalid-feedback">{{ $errors->first('total_value') }}</div>
                @endif
            </div>
        </div>
        <div class="text-lg-end ">
            <button type="button" class="btn btn-outline-dark">Clear</button>
            <button type="submit" class="btn btn-primary">Create</button>
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
                    onChange: function(contents) {
                            @this.set('description', contents); // Sync with Livewire model
                        
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
