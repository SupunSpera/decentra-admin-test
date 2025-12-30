<div class="right-side-panel" wire:ignore.self>
    <div class="container-fluid">
        <div class="row mt-2 p-0 pt-3" wire:ignore.self>
            <!-- Progress Indicator -->
            <div class="col-2 d-flex flex-column align-items-center">
                <div class="progress-indicator">
                    <div class="progress-step completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Select Coin</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step completed">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Select Network</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step active">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Deposit Address</div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-10">
                <!-- Select Coin Section -->
                <div class="mb-4">
                    <h6 class="mb-2 font-weight-bold">Select Coin</h6>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-left d-flex align-items-center justify-content-between" 
                                type="button" id="coinDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($selectedCoin)
                                <div class="d-flex align-items-center">
                                    <span class="coin-icon mr-2">🟣</span>
                                    <span>{{ $selectedCoin }}</span>
                                </div>
                            @else
                                <span>Select Coin</span>
                            @endif
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu w-100" aria-labelledby="coinDropdown">
                            @foreach($availableCoins as $coin)
                                <a class="dropdown-item" href="#" wire:click.prevent="selectCoin('{{ $coin }}')">
                                    <span class="coin-icon mr-2">🟣</span>
                                    {{ $coin }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Select Network Section -->
                <div class="mb-4">
                    <h6 class="mb-2 font-weight-bold">Select Network</h6>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-left d-flex align-items-center justify-content-between" 
                                type="button" id="networkDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($selectedNetwork)
                                <span>{{ $selectedNetwork['name'] }}</span>
                            @else
                                <span>Select Network</span>
                            @endif
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu w-100" aria-labelledby="networkDropdown">
                            @foreach($availableNetworks as $network)
                                <a class="dropdown-item" href="#" wire:click.prevent="selectNetwork({{ $network['id'] }})">
                                    {{ $network['name'] }} ({{ $network['chain_id'] }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @if($selectedNetwork && isset($selectedNetwork['tokens']) && !empty($selectedNetwork['tokens']))
                        <div class="mt-2 d-flex justify-content-between align-items-center text-muted small">
                            <span>Contract address ending in</span>
                            <a href="#" class="text-muted">
                                {{ substr(end(array_values($selectedNetwork['tokens'])), -6) }} 
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Deposit Address Section -->
                @if($selectedCoin && $selectedNetwork && $depositAddress)
                    <div class="mb-4">
                        <h6 class="mb-2 font-weight-bold">Deposit Address</h6>
                        
                        <!-- Deposit Info Box -->
                        <div class="deposit-address-box p-3 mb-3" style="background: #1a1a1a; border-radius: 8px;">
                            <div class="row">
                                <!-- QR Code -->
                                <div class="col-4 d-flex align-items-center justify-content-center">
                                    <div id="qrcode" style="width: 150px; height: 150px;"></div>
                                </div>
                                
                                <!-- Address Details -->
                                <div class="col-8">
                                    <label class="text-muted small mb-1">Address</label>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="address-text" style="flex: 1; word-break: break-all;">
                                            <span class="text-warning">{{ substr($depositAddress, 0, 6) }}</span>
                                            <span class="text-muted">{{ substr($depositAddress, 6, -6) }}</span>
                                            <span class="text-muted">{{ substr($depositAddress, -6) }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-link text-muted ml-2" onclick="copyAddress('{{ $depositAddress }}')" title="Copy Address">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <i class="fas fa-chevron-down text-muted ml-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Minimum Deposit -->
                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <span>Minimum deposit</span>
                            <span>More than 0.01 {{ $selectedCoin }}</span>
                        </div>
                    </div>
                @endif

                <!-- Close Button -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-warning slide-button" style="min-width: 150px;">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .right-side-panel {
        position: fixed;
        top: 0;
        right: -500px;
        width: 500px;
        height: 100vh;
        background: #1a1a1a;
        color: #fff;
        z-index: 1050;
        overflow-y: auto;
        transition: right 0.5s ease;
        box-shadow: -2px 0 10px rgba(0,0,0,0.3);
    }

    .progress-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #333;
        border: 2px solid #666;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 12px;
    }

    .progress-step.completed .step-circle {
        background: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    .progress-step.active .step-circle {
        background: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .step-label {
        font-size: 10px;
        margin-top: 5px;
        color: #999;
        text-align: center;
        white-space: nowrap;
    }

    .progress-step.completed .step-label,
    .progress-step.active .step-label {
        color: #fff;
    }

    .progress-line {
        width: 2px;
        height: 30px;
        background: #333;
        margin: 5px 0;
    }

    .progress-step.completed + .progress-line {
        background: #28a745;
    }

    .coin-icon {
        font-size: 20px;
    }

    .deposit-address-box {
        border: 1px solid #333;
    }

    .address-text {
        font-family: monospace;
        font-size: 14px;
    }

    .dropdown-menu {
        background: #2a2a2a;
        border: 1px solid #444;
    }

    .dropdown-item {
        color: #fff;
    }

    .dropdown-item:hover {
        background: #3a3a3a;
        color: #fff;
    }

    .btn-outline-secondary {
        border-color: #444;
        color: #fff;
        background: #2a2a2a;
    }

    .btn-outline-secondary:hover {
        background: #3a3a3a;
        border-color: #555;
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
    // Generate QR Code when deposit address is available
    @if($selectedCoin && $selectedNetwork && $depositAddress)
        document.addEventListener('DOMContentLoaded', function() {
            QRCode.toCanvas(document.getElementById('qrcode'), '{{ $depositAddress }}', {
                width: 150,
                colorDark: '#ffffff',
                colorLight: '#1a1a1a',
            }, function (error) {
                if (error) console.error(error);
            });
        });
    @endif

    // Copy address to clipboard
    function copyAddress(address) {
        navigator.clipboard.writeText(address).then(function() {
            $.alert({
                title: 'Copied!',
                content: 'Address copied to clipboard',
                type: 'green',
                autoClose: 'close|2000',
            });
        });
    }

    // Close button handler
    $('.slide-button').click(function() {
        var rightSidePanel = $('.right-side-panel');
        rightSidePanel.animate({
            right: '-500px'
        }, 500);
    });

    // Listen for deposit events
    window.addEventListener('deposit-success', event => {
        $.alert({
            title: '<h6 style="color:black !important;">Deposit Successful!</h6>',
            content: "<h7 style='color:black;'> Deposit address generated successfully! </h7>",
            type: 'green',
            autoClose: 'close|3000',
        });
    });

    window.addEventListener('went-wrong', event => {
        $.alert({
            title: '<h6 style="color:black !important;">Something went wrong!</h6>',
            content: "<h7 style='color:black;'> Something went wrong! </h7>",
            type: 'red',
        });
    });
</script>
@endpush









