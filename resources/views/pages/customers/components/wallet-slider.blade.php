<div class="right-side-panel" wire:ignore.self>
    <div class="container">
        <div class="row col-12 mt-2 p-0 pt-3" wire:ignore.self>

            <div class="col-12 p-0">
                <h4 class="my-2"> Deposit {{($type == 'USDT') ? 'USDT' : 'Frozen Tokens' }} </h4>
            </div>

        </div>

        <form wire:submit.prevent="submit">
            @csrf
            <div class="form-group row">
                <label for="inp_amount" class="col-sm-2 col-form-label">{{($type == 'USDT') ? 'USDT' : 'Token' }} Amount</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                        id="inp_amount" placeholder="Enter  Amount" wire:model="amount">
                    @if ($errors->has('amount'))
                        <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                    @endif
                </div>
            </div>
            <div class="text-lg-end ">
                <button type="button" class="btn btn-outline-dark slide-button">Back</button>
                <button type="submit" class="btn btn-primary">Deposit</button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('deposit-success', event => {

            $.alert({
                title: '<h6 style="color:black !important;">Deposit Succesfull!</h6>',
                content: "<h7 style='color:black;'> Amount Deposited Successfully! </h7>",
                type: 'green',
                autoClose: 'close|8000',
                buttons: {
                    close: function() {
                        // toggleSlider();
                        location.reload();
                    },
                }

            });
            window.removeEventListener('deposit-success', this);

        });


        window.addEventListener('went-wrong', event => {

            $.alert({
                title: '<h6 style="color:black !important;">Somthing went wrong!</h6>',
                content: "<h7 style='color:black;'> Somthing went wrong! </h7>",
                type: 'red',

            });
            window.removeEventListener('went-wrong', this);

        });
    </script>
@endpush
