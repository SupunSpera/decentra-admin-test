<div>
    <form wire:submit.prevent="submit" onsubmit="setProductTerms()">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="inp_terms" class="col col-form-label">Terms & Conditions</label>
                                    <div class="col-sm-12">
                                        <textarea
                                            id="inp_terms"
                                            class="form-control {{ $errors->has('terms') ? 'is-invalid' : '' }}"
                                            placeholder="Enter Product Terms"
                                            wire:model="terms"
                                            rows="5"></textarea>
                                            @if ($errors->has('terms'))
                                            <div class="invalid-feedback">{{ $errors->first('terms') }}</div>
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 text-center">
                                <button type="submit" class="btn btn-primary">Update Terms</button>
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
                // Variable to track if Summernote is focused
                let isFocused = false;

                // Initialize Summernote
                $('#inp_terms').summernote({
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
                        onFocus: function() {
                            isFocused = true;
                        },
                        onBlur: function() {
                            isFocused = false;
                            // Sync content when leaving the editor
                            let terms = $('#inp_terms').summernote('code');
                            // @this.set('terms', terms);
                        },
                        onChange: function(contents) {
                            // @this.set('terms', contents);
                        }
                    }
                });

                // Reinitialize Summernote after Livewire updates if not focused
                document.addEventListener('livewire:update', function() {
                    if (!isFocused) {
                        // let productTerms = @this.get('terms');
                        $('#inp_terms').summernote('code', productTerms);
                    }
                });
            });

            function setProductTerms() {
                let productTerms = $('#inp_terms').summernote('code');
                @this.set('terms', productTerms);
            }
        </script>
    @endpush
</div>
