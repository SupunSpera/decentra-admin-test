<?php

namespace App\Http\Controllers;

use domain\Facades\InstitutionalBonusFacade;


class InstitutionalBonusController extends Controller
{
      /**
     * Display the products
     *
     */
    public function all()
    {
        $institutional_bonus_total = InstitutionalBonusFacade::getTotalAmount();

        return view('pages.institutional_bonus.all',compact('institutional_bonus_total'));
    }
}
