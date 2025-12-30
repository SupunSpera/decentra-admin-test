<?php

namespace App\Http\Controllers;

use domain\Facades\ReferralFacade;

class ReferralController extends Controller
{
    /**
     * Display the customers
     *
     */
    public function all($id)
    {
        $referrals = ReferralFacade::getReferrals($id);
        return view('pages.referrals.all');
    }

    /**
     * Display the customers
     *
     */
    public function tree()
    {
        session()->forget('maxLevel'); // Clear the session value
    //    $referral_levels = ReferralFacade::getAllReferralsByLevelsWithLimit(10);
        return view('pages.referrals.tree');
    }

    /**
     * treeView
     *
     * @return
     */
    public function treeView(){
        $referral_levels = ReferralFacade::getAllReferralsByLevelsWithLimit(10);
        return view('pages.referrals.tree-view',compact('referral_levels'));
    }
}

