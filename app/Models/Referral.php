<?php

namespace App\Models;

use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\ReducedCustomerSupportingBonusFacade;
use domain\Facades\ReferralFacade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'parent_referral_id',
        'direct_referral_id',
        'left_child_id',
        'right_child_id',
        'level',
        'level_index',
        'left_points',
        'right_points',
        'status',
    ];


    /**
     * customer
     *
     * @return HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }


    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->where('customer_id', $id)->first();
    }

    /**
     * getReferrals
     *
     * @param  mixed $id
     * @return void
     */
    public function getReferrals($id)
    {
        return $this->where('parent_referral_id', $id)->get();
    }


    /**
     * getReferralsExceptCurrent
     *
     * @param  mixed $id
     * @return void
     */
    public function getReferralsExceptCurrent($id)
    {
        return $this->where('id', '<>', $id)->get();
    }


    /**
     * getReferralLevels
     *
     * @param  mixed $id
     * @return void
     */
    public function getReferralLevels($level)
    {
        return $this->select('level')->where('level', '>=', $level)->distinct()->get();
    }

    /**
     * getEmptyReferralByLevel
     *
     * @param  mixed $level
     * @return void
     */
    public function getEmptyReferralByLevel($level, $type)
    {
        if ($type == 'left') {
            return $this->where('level', $level)->whereNull('left_child_id')->first();
        } else {
            return $this->where('level', $level)->whereNull('right_child_id')->first();
        }
    }

    /**
     * getAllReferralsByLevels
     *
     */
    public function getAllReferralsByLevels()
    {
        $levels = $this->select('level')->distinct()->get();

        $data = array();
        foreach ($levels as $key => $levelData) {


            $elementCount = 2 ** $key; // element count at current level
            $levelElements = array();
            for ($i = 1; $i <= $elementCount; $i++) {

                //get referral with given level index in current level
                $referral = $this->select('referrals.id', 'referrals.left_child_id', 'referrals.right_child_id', 'referrals.level_index', 'referrals.customer_id', 'customers.email')
                    ->join('customers', 'referrals.customer_id', '=', 'customers.id')
                    ->where('referrals.level', $levelData->level)
                    ->where('referrals.level_index', $i)
                    ->orderBy('referrals.level_index', 'asc')
                    ->first();



                if ($referral) {

                    $leftChildReferral = CustomerSupportingBonusFacade::getChildReferrals($referral->id, 'left'); // get all left side child referrals
                    $rightChildReferral = CustomerSupportingBonusFacade::getChildReferrals($referral->id, 'right'); // get all right side child referrals



                    if ($leftChildReferral) {
                        $leftSideTotal = CustomerSupportingBonusFacade::getTodaySupportingBonusTotalByReferralAndCustomers($referral->id, $leftChildReferral); // get supporting bonus total of left side child referrals
                    } else {
                        $leftSideTotal = 0;
                    }

                    if ($rightChildReferral) {
                        $rightSideTotal = CustomerSupportingBonusFacade::getTodaySupportingBonusTotalByReferralAndCustomers($referral->id, $rightChildReferral); // get supporting bonus total of right side child referrals                }else {

                    } else {
                        $rightSideTotal = 0;
                    }


                    $availableLeftSideSupportingBonus = ReducedCustomerSupportingBonusFacade::getAvailableReducedSupportingBonusByCustomerAndSide($referral->customer_id, ReducedCustomerSupportingBonus::SIDE['LEFT']);
                    $availableRightSideSupportingBonus = ReducedCustomerSupportingBonusFacade::getAvailableReducedSupportingBonusByCustomerAndSide($referral->customer_id, ReducedCustomerSupportingBonus::SIDE['RIGHT']);

                    if ($availableLeftSideSupportingBonus) {
                        $referral['leftTotal'] = $leftSideTotal + $availableLeftSideSupportingBonus->amount;
                    } else {
                        $referral['leftTotal'] = $leftSideTotal;
                    }

                    if ($availableRightSideSupportingBonus) {

                        $referral['rightTotal'] = $rightSideTotal + $availableRightSideSupportingBonus->amount;
                    } else {
                        $referral['rightTotal'] = $rightSideTotal;
                    }
                } else {
                    $referral['leftTotal'] = 0;
                    $referral['rightTotal'] = 0;
                }


                if ($referral) {
                    array_push($levelElements, $referral);
                } else {
                    array_push($levelElements, array());
                }
            }
            array_push($data, $levelElements);
        }

        return $data;
    }

    /**
     * getAllReferralsByLevelsWithLimit
     *
     * @param  mixed $limit
     * @return void
     */
    public function getAllReferralsByLevelsWithLimit($limit)
    {
        $levels = $this->select('level')->distinct()->limit($limit)->get();

        $data = array();
        foreach ($levels as $key => $levelData) {


            $elementCount = 2 ** $key; // element count at current level
            $levelElements = array();
            for ($i = 1; $i <= $elementCount; $i++) {

                //get referral with given level index in current level
                $referral = $this->select('referrals.id', 'referrals.left_child_id', 'referrals.right_child_id', 'referrals.level_index', 'referrals.customer_id', 'customers.email')
                    ->join('customers', 'referrals.customer_id', '=', 'customers.id')
                    ->where('referrals.level', $levelData->level)
                    ->where('referrals.level_index', $i)
                    ->orderBy('referrals.level_index', 'asc')
                    ->first();



                if ($referral) {

                    $leftChildReferral = CustomerSupportingBonusFacade::getChildReferrals($referral->id, 'left'); // get all left side child referrals
                    $rightChildReferral = CustomerSupportingBonusFacade::getChildReferrals($referral->id, 'right'); // get all right side child referrals



                    if ($leftChildReferral) {
                        $leftSideTotal = CustomerSupportingBonusFacade::getTodaySupportingBonusTotalByReferralAndCustomers($referral->id, $leftChildReferral); // get supporting bonus total of left side child referrals
                    } else {
                        $leftSideTotal = 0;
                    }

                    if ($rightChildReferral) {
                        $rightSideTotal = CustomerSupportingBonusFacade::getTodaySupportingBonusTotalByReferralAndCustomers($referral->id, $rightChildReferral); // get supporting bonus total of right side child referrals                }else {

                    } else {
                        $rightSideTotal = 0;
                    }


                    $availableLeftSideSupportingBonus = ReducedCustomerSupportingBonusFacade::getAvailableReducedSupportingBonusByCustomerAndSide($referral->customer_id, ReducedCustomerSupportingBonus::SIDE['LEFT']);
                    $availableRightSideSupportingBonus = ReducedCustomerSupportingBonusFacade::getAvailableReducedSupportingBonusByCustomerAndSide($referral->customer_id, ReducedCustomerSupportingBonus::SIDE['RIGHT']);

                    if ($availableLeftSideSupportingBonus) {
                        $referral['leftTotal'] = $leftSideTotal + $availableLeftSideSupportingBonus->amount;
                    } else {
                        $referral['leftTotal'] = $leftSideTotal;
                    }

                    if ($availableRightSideSupportingBonus) {

                        $referral['rightTotal'] = $rightSideTotal + $availableRightSideSupportingBonus->amount;
                    } else {
                        $referral['rightTotal'] = $rightSideTotal;
                    }
                } else {
                    $referral['leftTotal'] = 0;
                    $referral['rightTotal'] = 0;
                }


                if ($referral) {
                    array_push($levelElements, $referral);
                } else {
                    array_push($levelElements, array());
                }
            }
            array_push($data, $levelElements);
        }

        return $data;
    }


    /**
     * getAllReferralsByLevelAndParents
     *
     * @param  mixed $level
     * @param  mixed $parent_ids
     * @return void
     */
    public function getAllReferralsByLevelAndParents($level, $parent_ids)
    {
        return  $this->where('level', $level)
            ->whereIn('parent_referral_id', $parent_ids)
            ->where(function ($query) {
                $query->whereNull('left_child_id')
                    ->orWhereNull('right_child_id');
            })
            ->get();
    }

    /**
     * getAllReferralIdsByLevelAndParents
     *
     * @param  mixed $level
     * @param  mixed $parent_ids
     * @return void
     */
    public function getAllReferralIdsByLevelAndParents($level, $parent_ids)
    {
        return  $this->select('referrals.id')
            ->where('level', $level)
            ->whereIn('parent_referral_id', $parent_ids)
            ->pluck('referrals.id');
    }

    /**
     * getDirectReferralCount
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getDirectReferralCount($referral_id)
    {
        return $this->where('direct_referral_id', $referral_id)
            ->count();
    }


    /**
     * getAllChildReferrals
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getAllChildReferrals($referral_id)
    {
        return $this->select('id')->where('parent_referral_id', $referral_id)
            ->pluck('id');
    }

    /**
     * getAllPurchasedChildReferrals
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getAllPurchasedChildReferrals($referral_id)
    {
        return $this->join('customers', 'referrals.customer_id', '=', 'customers.id')
            ->where('referrals.parent_referral_id', $referral_id)
            ->where('customers.purchased_status', Customer::PURCHASED_STATUS['ACTIVE'])
            ->pluck('customers.id');
    }

    /**
     * getDirectReferral
     *
     * @param  mixed $referral_id
     * @return mixed
     */
    public function getDirectReferrals($referral_id)
    {
        return $this->where('direct_referral_id', $referral_id)
            ->get();
    }

    /**
     * getCustomerById
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getCustomerById($referral_id)
    {
        return $this->select('customer_id')->where('id', $referral_id)
            ->pluck('customer_id');
    }


    // Define the relationship to get the left child
    public function leftChild()
    {
        return $this->hasOne(Referral::class, 'id', 'left_child_id');
    }

    // Define the relationship to get the right child
    public function rightChild()
    {
        return $this->hasOne(Referral::class, 'id', 'right_child_id');
    }

    /**
     * getCustomerIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    public function getCustomerIds($referralIds)
    {
        return $this->whereIn('id', $referralIds)
            ->pluck('customer_id');
    }


    /**
     * getReferralIds
     *
     * @param  mixed $customerIds
     * @return void
     */
    public function getReferralIds($customerIds)
    {
        return $this->whereIn('customer_id', $customerIds)
            ->pluck('id');
    }


    /**
     * getDirectReferralIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    public function getDirectReferralIds($referralIds)
    {
        return $this->whereIn('id', $referralIds)
            ->pluck('direct_referral_id');
    }


    /**
     * getProductPurchasedReferralsByDirectReferralIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    function getProductPurchasedReferralsByDirectReferralIds($referralIds, $date)
    {
        return  $this->distinct()
            ->select('direct_referral_id')
            ->whereIn('customer_id', function ($query) {
                $query->select('customer_id')
                    ->from('product_purchases');
            })
            ->whereDate('referrals.created_at', '>=', $date)
            ->whereIn('direct_referral_id', $referralIds)
            ->pluck('direct_referral_id');
    }

    /**
     * getMaxLevel
     *
     * @return void
     */
    public function getMaxLevel()
    {
        return Referral::max('level');
    }
}
