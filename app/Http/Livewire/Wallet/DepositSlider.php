<?php

namespace App\Http\Livewire\Wallet;

use domain\Facades\CryptoNetworkFacade;
use domain\Facades\WalletFacade;
use Livewire\Component;

class DepositSlider extends Component
{
    public $customer_id;
    public $wallet;
    
    public $selectedCoin = null;
    public $selectedNetwork = null;
    public $depositAddress = null;
    
    public $availableCoins = ['ETH', 'USDT', 'USDC', 'BTC'];
    public $availableNetworks = [];

    public function mount()
    {
        $this->wallet = WalletFacade::getByCustomerId($this->customer_id);
        
        // Load available crypto networks
        $networks = CryptoNetworkFacade::getActive();
        $this->availableNetworks = $networks->map(function($network) {
            $tokens = [];
            if ($network->tokens) {
                if (is_string($network->tokens)) {
                    try {
                        $tokens = json_decode($network->tokens, true) ?? [];
                    } catch (\Exception $e) {
                        $tokens = [];
                    }
                } else {
                    $tokens = $network->tokens;
                }
            }
            
            return [
                'id' => $network->id,
                'name' => $network->name,
                'chain_id' => $network->chain_id,
                'rpc_http' => $network->rpc_http,
                'rpc_ws' => $network->rpc_ws,
                'tokens' => $tokens,
            ];
        })->toArray();
        
        // Set default coin if available
        if (count($this->availableCoins) > 0) {
            $this->selectedCoin = $this->availableCoins[0];
        }
    }

    public function render()
    {
        return view('pages.customers.components.deposit-slider');
    }

    /**
     * Select coin
     */
    public function selectCoin($coin)
    {
        $this->selectedCoin = $coin;
        $this->selectedNetwork = null;
        $this->depositAddress = null;
    }

    /**
     * Select network
     */
    public function selectNetwork($networkId)
    {
        $network = collect($this->availableNetworks)->firstWhere('id', $networkId);
        
        if ($network) {
            $this->selectedNetwork = $network;
            
            // Generate deposit address (use wallet address for now)
            if ($this->wallet && $this->wallet->eth_wallet_address) {
                $this->depositAddress = $this->wallet->eth_wallet_address;
            } else {
                $this->depositAddress = null;
            }
        }
    }
}









