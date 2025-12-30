<?php

namespace domain\Services;

use App\Models\WalletDeposit;
use Illuminate\Database\Eloquent\Collection;

class WalletDepositService
{

    protected $walletDeposit;

    public function __construct()
    {
        $this->walletDeposit = new WalletDeposit();
    }
    /**
     * Get walletDeposit using id
     *
     * @param  int $id
     *
     * @return WalletDeposit
     */
    public function get(int $id): WalletDeposit
    {
        return $this->walletDeposit->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->walletDeposit->all();
    }
    /**
     * create
     *
     * @param  mixed $walletDeposit
     * @return WalletDeposit
     */
    public function create(array $walletDeposit): WalletDeposit
    {
        return $this->walletDeposit->create($walletDeposit);
    }
    /**
     * Update walletDeposit
     *
     * @param WalletDeposit $walletDeposit
     * @param array $data
     *
     *
     */
    public function update(WalletDeposit $walletDeposit, array $data)
    {
        return  $walletDeposit->update($this->edit($walletDeposit, $data));
    }
    /**
     * Edit walletDeposit
     *
     * @param WalletDeposit $walletDeposit
     * @param array $data
     *
     * @return array
     */
    protected function edit(WalletDeposit $walletDeposit, array $data): array
    {
        return array_merge($walletDeposit->toArray(), $data);
    }
    /**
     * Delete a walletDeposit
     *
     * @param WalletDeposit $walletDeposit
     *
     *
     */
    public function delete(WalletDeposit $walletDeposit)
    {
        return $walletDeposit->delete();
    }

      /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id) : ?WalletDeposit
    {
        return $this->walletDeposit->getByCustomerId($id);
    }


    /**
     * getExpiredDeposits
     *
     * @return Collection
     */
    public function getExpiredDeposits() :Collection
    {
        return $this->walletDeposit->getExpiredDeposits();
    }


    /**
     * sendTokenTransferRequest
     *
     * @param  mixed $address
     * @param  mixed $privet_key
     * @return void
     */
    public function sendTokenTransferRequest($address,$privet_key)
    {
        $client = new \GuzzleHttp\Client();

        $data = [
            'address' => $address,
            'privet_key' => $privet_key,
        ];


        try {

            // $response = $client->post('http://159.65.150.132:7100/api/wallet/token-transfer', [

            $response = $client->post(config('path.node_backend_url').'/api/wallet/token-transfer', [
                'json' => $data,
            ]);

            if ($response->getStatusCode() === 200) {
                $responseBody = $response->getBody()->getContents();

                return array(
                    'message' => 'Token Transfer successful',
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


}
