<?php

namespace App\Http\Controllers;

use domain\Facades\CustomerFacade;
use domain\Facades\Gift\NfcCustomerFacade;
use domain\Facades\ProductFacade;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * home
     *
     * @return void
     */
    public function home()
    {
        $products = ProductFacade::all();
        $customers = CustomerFacade::all();
        $response = [
            'products_count' => count($products),
            'customers_count' => count($customers),
        ];
        return view('pages.dashboard.dashboard')->with($response);
    }
}
