<?php

namespace Database\Seeders;

use domain\Facades\UserFacade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserFacade::create(array(
            'name' => 'Admin',
            'email' => 'sysadmin@decentrax.uk',
            'password' => Hash::make('T6BS8LHv7i1ZHoV')
        ));
    }
}
