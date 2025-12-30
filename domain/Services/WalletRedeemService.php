<?php

namespace domain\Services;


use App\Models\WalletRedeem;
use Illuminate\Database\Eloquent\Collection;


class WalletRedeemService
{

    protected $walletRedeem;

    public function __construct()
    {
        $this->walletRedeem = new WalletRedeem();
    }
    /**
     * Get walletRedeem using id
     *
     * @param  int $id
     *
     * @return WalletRedeem
     */
    public function get(int $id): WalletRedeem
    {
        return $this->walletRedeem->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->walletRedeem->all();
    }
    /**
     * create
     *
     * @param  mixed $walletRedeem
     * @return WalletRedeem
     */
    public function create(array $walletRedeem): WalletRedeem
    {
        return $this->walletRedeem->create($walletRedeem);
    }
    /**
     * Update walletRedeem
     *
     * @param WalletRedeem $walletRedeem
     * @param array $data
     *
     *
     */
    public function update(WalletRedeem $walletRedeem, array $data)
    {
        return  $walletRedeem->update($this->edit($walletRedeem, $data));
    }
    /**
     * Edit walletRedeem
     *
     * @param WalletRedeem $walletRedeem
     * @param array $data
     *
     * @return array
     */
    protected function edit(WalletRedeem $walletRedeem, array $data): array
    {
        return array_merge($walletRedeem->toArray(), $data);
    }
    /**
     * Delete a walletRedeem
     *
     * @param WalletRedeem $walletRedeem
     *
     *
     */
    public function delete(WalletRedeem $walletRedeem)
    {
        return $walletRedeem->delete();
    }


    /**
     * getByWalletAddress
     *
     * @param  mixed $address
     * @return WalletRedeem
     */
    public function getByWalletAddress($address) :?WalletRedeem
    {
        return $this->walletRedeem->getByWalletAddress($address);
    }


    /**
     * sendTokenWithdrawRequest
     *
     * @param  mixed $address
     * @param  mixed $amount
     * @return void
     */
    public function sendTokenWithdrawRequest($redeem_id,$address,$amount)
    {
        $client = new \GuzzleHttp\Client();

        $data = [
            'redeem_id' => $redeem_id,
            'address' => $address,
            'amount' => $amount,
        ];

        try {




            // $response = $client->post('http://159.65.150.132:7100/api/wallet/token-withdraw', [

            $response = $client->post(config('path.node_backend_url').'/api/wallet/token-withdraw', [
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
