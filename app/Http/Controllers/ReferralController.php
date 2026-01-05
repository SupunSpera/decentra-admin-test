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
    public function treeView($rootId = null){
        // Only load first 5 levels initially for better performance
        $initialLimit = 5;

        // If rootId provided, start tree from that node
        if ($rootId) {
            $rootNode = ReferralFacade::get($rootId);
            if ($rootNode) {
                $rootNode->load('customer');
                $rootNode->leftTotal = $rootNode->left_children_count ?? 0;
                $rootNode->rightTotal = $rootNode->right_children_count ?? 0;
                $referral_levels = [[$rootNode]]; // Wrap in array format
                $parentNodeId = $rootNode->parent_referral_id; // For back navigation
            } else {
                // If invalid root, load default
                $referral_levels = ReferralFacade::getAllReferralsByLevelsWithLimit($initialLimit);

                $parentNodeId = null;
            }
        } else {
            // Default: load from top
            $referral_levels = ReferralFacade::getAllReferralsByLevelsWithLimit($initialLimit);
            $parentNodeId = null;
        }

        // dd($referral_levels, $rootId, $parentNodeId, $initialLimit);

        return view('pages.referrals.tree-view', compact('referral_levels', 'initialLimit', 'rootId', 'parentNodeId'));
    }
}

