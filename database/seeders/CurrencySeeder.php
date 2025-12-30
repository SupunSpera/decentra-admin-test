<?php

namespace Database\Seeders;

use domain\Facades\CurrencyPoolFacade;
use domain\Facades\SettingFacade;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CurrencyPoolFacade::create(
            array(
                'usdt_amount' => 100000,
                'tex_amount' => 100000
            )
        );

        SettingFacade::create(
            array(
                'daily_income_cap' => 100,
                'share_value' => 3,
                'usd_to_bte_fee'=>3,
                'bte_to_usd_fee'=>3,
                'withdrawal_fee'=>5

            )
        );
    }
}
