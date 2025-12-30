<?php

namespace domain\Services;


use App\Models\Wallet;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class WalletService
{

    protected $wallet;

    public function __construct()
    {
        $this->wallet = new Wallet();
    }
    /**
     * Get wallet using id
     *
     * @param  int $id
     *
     * @return Wallet
     */
    public function get(int $id): Wallet
    {
        return $this->wallet->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->wallet->all();
    }
    /**
     * create
     *
     * @param  mixed $wallet
     * @return Wallet
     */
    public function create(array $wallet): Wallet
    {
        return $this->wallet->create($wallet);
    }
    /**
     * Update wallet
     *
     * @param Wallet $wallet
     * @param array $data
     *
     *
     */
    public function update(Wallet $wallet, array $data)
    {
        return  $wallet->update($this->edit($wallet, $data));
    }
    /**
     * Edit wallet
     *
     * @param Wallet $wallet
     * @param array $data
     *
     * @return array
     */
    protected function edit(Wallet $wallet, array $data): array
    {
        return array_merge($wallet->toArray(), $data);
    }
    /**
     * Delete a wallet
     *
     * @param Wallet $wallet
     *
     *
     */
    public function delete(Wallet $wallet)
    {
        return $wallet->delete();
    }

    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->wallet->getByCustomerId($id);
    }


    /**
     * getByWalletAddress
     *
     * @param  mixed $address
     * @return void
     */
    public function getByWalletAddress($address)
    {
        return $this->wallet->getByWalletAddress($address);
    }

    /**
     * getAllWalletAddresses
     *
     * Get all eth_wallet_address values from wallets table
     *
     * @return array
     */
    public function getAllWalletAddresses(): array
    {
        return $this->wallet->getAllWalletAddresses();
    }

    /**
     * getAllWalletAddressesWithCustomerId
     *
     * Get all eth_wallet_address values with their customer_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWalletAddressesWithCustomerId()
    {
        return $this->wallet->getAllWalletAddressesWithCustomerId();
    }

    /**
     * createETHWallet
     *
     * @return void
     */
    public function createETHWallet()
    {
        $client = new \GuzzleHttp\Client();

        $data = [
            // Replace with your data to be sent in the request body
            // 'key' => 'value',
            // ... other data
        ];

        try {
            // $response = $client->post('http://159.65.150.132:7100/api/wallet/create', [
                $response = $client->post(config('path.node_backend_url').'/api/wallet/create', [
                'json' => $data,
            ]);

            if ($response->getStatusCode() === 200) {
                $responseBody = $response->getBody()->getContents();

                return array(
                    'message' => 'Wallet created successfully',
                    'response' => $responseBody
                );
            } else {
                $responseBody = $response->getBody()->getContents();

                return array(
                    'message' => 'Error creating wallet',
                    'response' => $responseBody
                );
            }
        } catch (\Exception $e) {


            return array(
                'message' => 'Error creating wallet',
                'response' => $e->getMessage()
            );
        }
    }

    /**
     * getETHWalletBalance
     *
     * @param  mixed $address
     * @return void
     */
    function getETHWalletBalance($address)
    {
        $client = new \GuzzleHttp\Client();

        $data = [
            'address' => $address

        ];

        try {


            // $response = $client->get('http://159.65.150.132:7100/api/wallet/token-balance', [
            $response = $client->get(config('path.node_backend_url') . '/api/wallet/token-balance', [
                'json' => $data,
            ]);

            if ($response->getStatusCode() === 200) {
                $responseBody = $response->getBody()->getContents();

                return array(
                    'message' => 'Balance found successful',
                    'response' => $responseBody
                );
            } else {
                $responseBody = $response->getBody()->getContents();

                return array(
                    'message' => 'Error creating wallet',
                    'response' => $responseBody
                );
            }
        } catch (\Exception $e) {


            return array(
                'message' => 'Error creating wallet',
                'response' => $e->getMessage()
            );
        }
    }

    /**
     * Get wallet subscriptions for the listener
     * Returns wallets with their network and monitored tokens
     *
     * @return array
     */
    public function getWalletSubscriptions(): array
    {
        $wallets = $this->wallet
            ->whereNotNull('eth_wallet_address')
            ->whereNotNull('crypto_network_id')
            ->with('cryptoNetwork')
            ->get();

        $subscriptions = [];

        foreach ($wallets as $wallet) {
            if (!$wallet->cryptoNetwork || !$wallet->cryptoNetwork->is_active) {
                continue;
            }

            // Default to NATIVE if no monitored_tokens specified
            $tokens = $wallet->monitored_tokens ?? ['NATIVE'];

            $subscriptions[] = [
                'user_id' => $wallet->customer_id,
                'userId' => $wallet->customer_id, // Alias for compatibility
                'chain_id' => $wallet->cryptoNetwork->chain_id,
                'network' => $wallet->cryptoNetwork->name,
                'wallet_address' => $wallet->eth_wallet_address,
                'wallet' => $wallet->eth_wallet_address, // Alias for compatibility
                'tokens' => $tokens,
            ];
        }

        return $subscriptions;
    }
}
