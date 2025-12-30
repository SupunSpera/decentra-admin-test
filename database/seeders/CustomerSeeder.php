<?php

namespace Database\Seeders;

use domain\Facades\CustomerFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\WalletFacade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Traits\Encrypt\EncryptHelper;
use Nadun\EthWallet\Facades\EthWallet as NewEthWallet;

class CustomerSeeder extends Seeder
{
    use EncryptHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $customer = CustomerFacade::create(
            array(
                'first_name' => 'DecentraX',
                'last_name' => 'Main',
                'email' => 'info@decentrax.uk',
                'referral_code' => 'DX10000',
                'password' => Hash::make('decentra2026')
            )
        );

        if($customer){
            ReferralFacade::create(
                array(
                    'customer_id' => $customer->id,
                    'level' => 0,
                    'level_index' => 1,
                )
            );

            $ETHWallet = NewEthWallet::generate(); 

            // $data = json_decode($ETHWallet['response']);
            $encryptedPrivateKey = $this->custom_encrypt($ETHWallet['privateKey']);
                

            WalletFacade::create(array(
                'customer_id'=>$customer->id,
                'eth_wallet_address' => $ETHWallet['address'],
                'eth_wallet_private_key' => $encryptedPrivateKey
            ));
        }
    }
}
