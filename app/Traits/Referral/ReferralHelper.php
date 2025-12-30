<?php

namespace App\Traits\Referral;

use domain\Facades\ReferralFacade;


/**
 * Referral Helper
 *
 * php version 8
 *
 * @category Trait
 * @author   Spera Labs
 * @license  https://decentrax.com Config
 * @link     https://decentrax.com/
 * */

trait ReferralHelper
{

    /**
     * findNextAvailableChild
     *
     * @param  mixed $level
     * @return void
     */
    function findNextAvailableChild($level)
    {
        $referralLevels = ReferralFacade::getReferralLevels($level);

        foreach ($referralLevels as $referralLevel) {

            $leftReferral = ReferralFacade::getEmptyReferralByLevel($referralLevel->level, 'left');

            if ($leftReferral == null) { // if all left children of selected level is filled
                $rightReferral = ReferralFacade::getEmptyReferralByLevel($referralLevel->level, 'right');

                if ($rightReferral == null) { // if all right children of selected level is filled
                    continue; // continue to next level
                } else {
                    return $rightReferral;
                }
            } else {
                return $leftReferral;
            }
        }
    }


    /**
     * findNextAvailableChildUnderThisParent
     *
     * @param  mixed $level
     * @param  mixed $parentId
     * @return void
     */
    function findNextAvailableChildUnderThisParent($level, $parentId)
    {

        $referralLevels = ReferralFacade::getReferralLevels($level);
        $parentIds = array($parentId);

        foreach ($referralLevels as $referralLevel) {

            $childReferrals = ReferralFacade::getAllReferralsByLevelAndParents($referralLevel->level, $parentIds);

            if (count($childReferrals) == 0) { // if all  children of selected level is filled

                // get next level children of this parent
                $referralIds = ReferralFacade::getAllReferralIdsByLevelAndParents($referralLevel->level, $parentIds);
                // Remove all existing elements
                $parentIds = [];
                $parentIds = $referralIds;
                continue; // continue to next level

            } else {

                return $childReferrals[0];
            }
        }
    }

    /**
     * calculateLevelIndex
     *
     * @param  mixed $parentIndex
     * @param  mixed $childIndex
     * @return void
     */
    function calculateLevelIndex($parentIndex, $childIndex)
    {
        return $parentIndex + $childIndex + ($parentIndex - 2);
    }

    /**
     * getOuterChildren
     *
     * @param  mixed $parentId
     * @return void
     */
    function getOuterChildren($parentId)
    {
        $parent = ReferralFacade::get($parentId);

        $leftChild = ReferralFacade::get(intval($parent->left_child_id));
        $rightChild = ReferralFacade::get(intval($parent->right_child_id));

        while (true) {
            $leftGrandChild = $leftChild->left_child_id;
            $rightGrandChild = $rightChild->right_child_id;

            if ($leftGrandChild == null) { // if left grand child is null
                return array(
                    'child' => $leftChild->id,
                    'side' => 'left'
                );

                break;
            } else if ($rightGrandChild == null) { // if right grand child is null
                return array(
                    'child' => $rightChild->id,
                    'side' => 'right'
                );

                break;
            } else {

                $leftChild = ReferralFacade::get(intval($leftGrandChild));
                $rightChild = ReferralFacade::get(intval($rightGrandChild));
            }
        }
    }



    /**
     * getOuterChildWithSide
     *
     * @param  mixed $parentId
     * @param  mixed $side
     * @return void
     */
    function getOuterChildWithSide($parentId, $side)
    {
        $parent = ReferralFacade::get($parentId);

        if ($side == 'LEFT') {
            $child = ReferralFacade::get(intval($parent->left_child_id));
        } else {
            $child = ReferralFacade::get(intval($parent->right_child_id));
        }

        while (true) {

            if ($side == 'LEFT') {
                $grandChild = $child->left_child_id;
            } else {
                $grandChild = $child->right_child_id;
            }


            if ($grandChild == null) { // if left grand child is null
                if ($side == 'LEFT') {
                    return array(
                        'child' => $child->id,
                        'side' => 'left'
                    );
                } else {
                    return array(
                        'child' => $child->id,
                        'side' => 'right'
                    );
                }

                break;
            } else {
                $child = ReferralFacade::get(intval($grandChild));
            }
        }
    }
}
