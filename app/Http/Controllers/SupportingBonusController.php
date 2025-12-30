<?php

namespace App\Http\Controllers;

use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\SupportingBonusFacade;


class SupportingBonusController extends Controller
{
      /**
     * Display the products
     *
     */
    public function all()
    {
        $supporting_bonus_total = SupportingBonusFacade::getTotalAmount();
        return view('pages.supporting_bonus.all',compact('supporting_bonus_total'));
    }

    public function calculateSupportingBonus()
    {
        CustomerSupportingBonusFacade::makeSupportingBonusAvailable();
        return redirect()->route('supporting_bonus.all')->with('alert-success','Supporting Bonus Calculated Successfully');
    }


    /**
     * allocatedShares
     *
     * @return void
     */
    public function allocatedShares()
    {
        return view('pages.supporting_bonus.allocated_shares');
    }


    /**
     * generatedBonus
     *
     * @return void
     */
    public function generatedBonus()
    {
        return view('pages.supporting_bonus.generated_bonuses');
    }
}
