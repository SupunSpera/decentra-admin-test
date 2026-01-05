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
     * treeView - Display referral tree with optional focus on specific node
     *
     * @param int|null $rootId - Optional: Focus tree on this referral as root
     * @return
     */
    public function treeView($rootId = null)
    {
        $initialLimit = 5;
        $focusedNode = null;
        $breadcrumb = [];

        if ($rootId) {
            // Focus mode: Show tree starting from specific node
            $focusedNode = ReferralFacade::get($rootId);

            if (!$focusedNode) {
                return redirect()->route('referrals.tree-view')->with('error', 'Referral not found');
            }

            // Load customer relationship and calculate totals
            $focusedNode->load('customer');
            $focusedNode->leftTotal = $focusedNode->left_children_count ?? 0;
            $focusedNode->rightTotal = $focusedNode->right_children_count ?? 0;

            // Build breadcrumb path to root
            $breadcrumb = $this->buildBreadcrumb($focusedNode);

            // IMPORTANT: Create proper tree structure with focused node as single root
            // This ensures the node appears centered at the top
            $referral_levels = [[$focusedNode]];

        } else {
            // Normal mode: Show from absolute root
            $referral_levels = ReferralFacade::getAllReferralsByLevelsWithLimit($initialLimit);
        }

        return view('pages.referrals.tree-view', compact('referral_levels', 'initialLimit', 'focusedNode', 'breadcrumb'));
    }

    /**
     * buildBreadcrumb - Build path from focused node to root
     *
     * @param $node
     * @return array
     */
    private function buildBreadcrumb($node)
    {
        $breadcrumb = [];
        $current = $node;

        // Traverse up to root
        while ($current) {
            array_unshift($breadcrumb, [
                'id' => $current->id,
                'name' => $current->customer->first_name ?? $current->customer->email ?? 'User',
                'code' => $current->customer->referral_code ?? $current->id
            ]);

            if ($current->parent_referral_id) {
                $current = ReferralFacade::get($current->parent_referral_id);
            } else {
                break;
            }
        }

        return $breadcrumb;
    }
}

