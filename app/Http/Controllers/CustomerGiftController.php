<?php

namespace App\Http\Controllers;

class CustomerGiftController extends Controller
{
    /**
     * all
     *
     * @return void
     */
    public function all()
    {
        return view('pages.gifts.customer-gifts');

    }
}
