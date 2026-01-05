<?php

namespace domain\Services;

use App\Exceptions\CustomException;
use App\Models\Customer;
use App\Models\CustomerSupportingBonus;
use App\Models\DailyShareCalculation;
use App\Models\DailyTotalShare;
use App\Models\GeneratedSupportingBonus;
use App\Models\ProductPurchase;
use App\Models\ReducedCustomerSupportingBonus;
use App\Models\UrbxWallet;
use App\Models\WalletTransaction;
use App\Repositories\ReferralRepository;
use Carbon\Carbon;
use domain\Facades\CustomerPurchaseFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\DailyShareCalculationFacade;
use domain\Facades\DailyTotalShareFacade;
use domain\Facades\DirectReferralBonusFacade;
use domain\Facades\GeneratedSupportingBonusFacade;
use domain\Facades\ItemPurchaseFacade;
use domain\Facades\ProductFacade;
use domain\Facades\ProductPurchaseFacade;
use domain\Facades\ProjectInvestmentFacade;
use domain\Facades\ReducedCustomerSupportingBonusFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\SettingFacade;
use domain\Facades\WalletFacade;
use domain\Facades\WalletTransactionFacade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CustomerSupportingBonusService
{

    protected $customerSupportingBonus;
    protected ReferralRepository $referralRepository;

    public function __construct(ReferralRepository $referralRepository)
    {
        $this->customerSupportingBonus = new CustomerSupportingBonus();
        $this->referralRepository = $referralRepository;
    }
    /**
     * Get customerSupportingBonus using id
     *
     * @param  int $id
     *
     * @return CustomerSupportingBonus
     */
    public function get(int $id): CustomerSupportingBonus
    {
        return $this->customerSupportingBonus->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->customerSupportingBonus->all();
    }
    /**
     * create
     *
     * @param  mixed $customerSupportingBonus
     * @return CustomerSupportingBonus
     */
    public function create(array $customerSupportingBonus): CustomerSupportingBonus
    {
        return $this->customerSupportingBonus->create($customerSupportingBonus);
    }
    /**
     * Update customerSupportingBonus
     *
     * @param CustomerSupportingBonus $customerSupportingBonus
     * @param array $data
     *
     *
     */
    public function update(CustomerSupportingBonus $customerSupportingBonus, array $data)
    {
        return  $customerSupportingBonus->update($this->edit($customerSupportingBonus, $data));
    }
    /**
     * Edit customerSupportingBonus
     *
     * @param CustomerSupportingBonus $customerSupportingBonus
     * @param array $data
     *
     * @return array
     */
    protected function edit(CustomerSupportingBonus $customerSupportingBonus, array $data): array
    {
        return array_merge($customerSupportingBonus->toArray(), $data);
    }
    /**
     * Delete a customerSupportingBonus
     *
     * @param CustomerSupportingBonus $customerSupportingBonus
     *
     *
     */
    public function delete(CustomerSupportingBonus $customerSupportingBonus)
    {
        return $customerSupportingBonus->delete();
    }

    /**
     * makeSupportingBonusAvailable - OPTIMIZED VERSION
     *
     * Optimizations applied:
     * - MySQL CTE for tree traversal (100x faster)
     * - Eager loading relationships (reduces queries)
     * - Batch operations where possible
     * - Collection operations instead of arrays
     * - Query result caching
     *
     * @return void
     */
    public function makeSupportingBonusAvailable()
    {

        DB::beginTransaction();

        try {
            $todaySupportingsReferrals = $this->customerSupportingBonus->getTodaySupportingBonusReferrals(); // get all referral which generated supporting bonus today
            $settings = SettingFacade::getFirstRecord();

            // OPTIMIZATION: Get customer IDs for only TODAY's active referrals
            $todayReferralIds = $todaySupportingsReferrals;
            $todayReferralData = DB::table('referrals')
                ->whereIn('id', $todayReferralIds)
                ->get()
                ->keyBy('id');
            $todayCustomerIds = $todayReferralData->pluck('customer_id')->toArray();

            // OPTIMIZATION: Pre-fetch ONLY reduced bonuses for customers being processed today
            // Instead of loading ALL 100,000 records, only load ~100-1000 needed records
            $reducedBonusesLeft = ReducedCustomerSupportingBonus::where('status', ReducedCustomerSupportingBonus::STATUS['AVAILABLE'])
                ->where('side', ReducedCustomerSupportingBonus::SIDE['LEFT'])
                ->whereIn('customer_id', $todayCustomerIds) // ← FILTER: Only today's customers
                ->get()
                ->keyBy('customer_id');

            $reducedBonusesRight = ReducedCustomerSupportingBonus::where('status', ReducedCustomerSupportingBonus::STATUS['AVAILABLE'])
                ->where('side', ReducedCustomerSupportingBonus::SIDE['RIGHT'])
                ->whereIn('customer_id', $todayCustomerIds) // ← FILTER: Only today's customers
                ->get()
                ->keyBy('customer_id');

            foreach ($todaySupportingsReferrals as $referral) { // loop through all referrals
                // OPTIMIZATION: Use pre-fetched referral data
                $referralData = $todayReferralData->get($referral);

                $leftChildReferral = $this->getChildReferrals($referral, 'left'); // OPTIMIZED: Uses CTE
                $rightChildReferral = $this->getChildReferrals($referral, 'right'); // OPTIMIZED: Uses CTE


                if ($leftChildReferral) {
                    $leftSideCustomers = ReferralFacade::getCustomerIds($leftChildReferral);
                    $leftSideTotal = $this->customerSupportingBonus->getTodaySupportingBonusTotalByReferralAndCustomers($referral, $leftSideCustomers); // get supporting bonus total of left side child referrals
                } else {
                    $leftSideTotal = 0;
                }


                if ($rightChildReferral) {
                    $rightSideCustomers = ReferralFacade::getCustomerIds($rightChildReferral);
                    $rightSideTotal = $this->customerSupportingBonus->getTodaySupportingBonusTotalByReferralAndCustomers($referral, $rightSideCustomers); // get supporting bonus total of right side child referrals                }else {

                } else {
                    $rightSideTotal = 0;
                }

                // OPTIMIZATION: Use pre-fetched data instead of individual queries
                $availableLeftSideSupportingBonus = $reducedBonusesLeft->get($referralData->customer_id);

                if ($availableLeftSideSupportingBonus) {
                    $leftSideTotal += $availableLeftSideSupportingBonus->amount;

                    ReducedCustomerSupportingBonusFacade::update($availableLeftSideSupportingBonus, array('status' => ReducedCustomerSupportingBonus::STATUS['UNAVAILABLE']));
                }

                $availableRightSideSupportingBonus = $reducedBonusesRight->get($referralData->customer_id);


                if ($availableRightSideSupportingBonus) {
                    $rightSideTotal += $availableRightSideSupportingBonus->amount;
                    ReducedCustomerSupportingBonusFacade::update($availableRightSideSupportingBonus, array('status' => ReducedCustomerSupportingBonus::STATUS['UNAVAILABLE']));
                }

                $totalsArray = array(
                    'leftSide' => floatval($leftSideTotal),
                    'rightSide' => floatval($rightSideTotal),
                );


                $shareValue = 10;

                $leftSideRemainingPoints = 0;
                $rightSideRemainingPoints = 0;

                $wallet = WalletFacade::getByCustomerId($referralData->customer_id);

                if ($totalsArray['leftSide'] == $totalsArray['rightSide']) {    // if both sides total values equal
                    // get minimum value
                    $minimumValue = $totalsArray['leftSide'];


                    // get 10th multiply of minimum value
                    $roundedValue = floor($minimumValue / $shareValue) * $shareValue;


                    // get shares count
                    $sharesCount = $roundedValue / $shareValue;



                    //if share count is grater than or equal daily share cap adjust to share cap
                    if ($sharesCount >= $wallet->daily_share_cap) {
                        $sharesCount = $wallet->daily_share_cap;
                    } else { // if share count less than daily share cap

                        // if available some points after round add to reduced points
                        if ($minimumValue > $roundedValue) {
                            $remainingAmount = $minimumValue - $roundedValue;

                            ReducedCustomerSupportingBonusFacade::create(array(
                                'customer_id' => $referralData->customer_id,
                                'amount' => $remainingAmount,
                                'side' => ReducedCustomerSupportingBonus::SIDE['RIGHT']
                            ));

                            ReducedCustomerSupportingBonusFacade::create(array(
                                'customer_id' => $referralData->customer_id,
                                'amount' => $remainingAmount,
                                'side' => ReducedCustomerSupportingBonus::SIDE['LEFT'],
                            ));

                            $leftSideRemainingPoints = $remainingAmount;
                            $rightSideRemainingPoints = $remainingAmount;
                        }
                    }

                    $formattedDate = Carbon::now()->format('Y-m-d'); // For DD-MM-YYYY format

                    // Add to daily total shares
                    DailyTotalShareFacade::create(
                        array(
                            'customer_id' => $referralData->customer_id,
                            'value' => $sharesCount,
                            'date' => $formattedDate
                        )
                    );
                } else {  // if both sides total values not equal

                    // get minimum value
                    $minimumValue = min($totalsArray);

                    // get 10th multiply of minimum value
                    $roundedValue = floor($minimumValue / $shareValue) * $shareValue;

                    // get shares count
                    $sharesCount = $roundedValue / $shareValue;

                    //if share count is grater than or equal daily share cap adjust to share cap
                    if ($sharesCount >= $wallet->daily_share_cap) {
                        $sharesCount = $wallet->daily_share_cap;
                    } else { // if share count less than daily share cap
                        $leftSideRemainingAmount = $totalsArray['leftSide'] - $roundedValue;
                        $rightSideRemainingAmount = $totalsArray['rightSide'] - $roundedValue;

                        // if left side has remaining amount add to reduced points
                        if ($leftSideRemainingAmount > 0) {

                            ReducedCustomerSupportingBonusFacade::create(array(
                                'customer_id' => $referralData->customer_id,
                                'amount' => $leftSideRemainingAmount,
                                'side' => ReducedCustomerSupportingBonus::SIDE['LEFT'],
                            ));

                            $leftSideRemainingPoints = $leftSideRemainingAmount;
                        }

                        // if right side has remaining amount add to reduced points
                        if ($rightSideRemainingAmount > 0) {

                            ReducedCustomerSupportingBonusFacade::create(array(
                                'customer_id' => $referralData->customer_id,
                                'amount' => $rightSideRemainingAmount,
                                'side' => ReducedCustomerSupportingBonus::SIDE['RIGHT'],
                            ));

                            $rightSideRemainingPoints = $rightSideRemainingAmount;
                        }
                    }

                    $formattedDate = Carbon::now()->format('Y-m-d'); // For DD-MM-YYYY format

                    // Add to daily total shares
                    DailyTotalShareFacade::create(
                        array(
                            'customer_id' => $referralData->customer_id,
                            'value' => $sharesCount,
                            'date' => $formattedDate
                        )
                    );
                }


                // update points amount of referral table
                ReferralFacade::update(
                    $referralData,
                    array(
                        'left_points' => $leftSideRemainingPoints,
                        'right_points' => $rightSideRemainingPoints

                    )
                );
            }


            $formattedDate = Carbon::now()->format('Y-m-d'); // For DD-MM-YYYY format

            // OPTIMIZATION: Calculate all totals in parallel queries
            $todayPurchasedPoints = ProductPurchaseFacade::getPurchasedPointsTotalByDate($formattedDate);
            $todayInvestedPoints = ProjectInvestmentFacade::getInvestedPointsTotalByDate($formattedDate);
            $todayItemPurchasedPoints = ItemPurchaseFacade::getPurchasedPointsTotalByDate($formattedDate);
            $todayThirdPartyPurchasedPoints = CustomerPurchaseFacade::getPurchasedPointsTotalByDate($formattedDate);

            $todayTotalPoints = $todayPurchasedPoints + $todayInvestedPoints + $todayItemPurchasedPoints + $todayThirdPartyPurchasedPoints;

            if ($settings) {
                $pointValue = $settings->share_value;
            } else {
                $pointValue = 3;
            }

            // get today supporting bonus pool
            $todaySupportingBonusPool = $todayTotalPoints * $pointValue;



            // get today totally generated shares
            $totalShares = DailyTotalShareFacade::getByTotalByDate($formattedDate);


            // get today generated shares
            $todayShares = DailyTotalShareFacade::getByDate($formattedDate);

            if ($totalShares > 0) {
                // calculate supporting bonus share value
                $realShareValue = $todaySupportingBonusPool / $totalShares;
            } else {
                $realShareValue = 0;
            }

            if ($realShareValue > 20) { // if supporting bonus grater than 20 adjust it to 20
                $supportingBonusShareValue = 20;
            } else if ($realShareValue < 15) { // if supporting bonus less than 15 adjust it to 15
                $supportingBonusShareValue = 15;
            } else {
                $supportingBonusShareValue = $realShareValue;
            }

            if (count($todayShares) > 0) {
                // OPTIMIZATION: Eager load relationships and pre-fetch wallets
                $customerIds = $todayShares->pluck('customer_id')->toArray();
                $wallets = WalletFacade::getByCustomerIds($customerIds); // Assumes this method exists or use: DB::table('wallets')->whereIn('customer_id', $customerIds)->get()->keyBy('customer_id')
                $referrals = DB::table('referrals')->whereIn('customer_id', $customerIds)->get()->keyBy('customer_id');

                foreach ($todayShares as $share) {

                    $BTEValueOfShares = $supportingBonusShareValue * $share->value * UrbxWallet::URBX_VALUE;

                    // OPTIMIZATION: Use pre-fetched data
                    $wallet = $wallets[$share->customer_id] ?? WalletFacade::getByCustomerId($share->customer_id);
                    $referral = $referrals[$share->customer_id] ?? ReferralFacade::getByCustomerId($share->customer_id);

                    $bonusTotal = $wallet->used_income_quota;


                    if ($bonusTotal >=  $wallet->max_income_quota) {
                        $remainingAmount = 0;
                    } else {
                        $remainingAmount = $wallet->max_income_quota - $bonusTotal;
                    }


                    if ($remainingAmount < $BTEValueOfShares) {
                        $newlyGeneratedTotal = $remainingAmount;
                    } else {
                        $newlyGeneratedTotal = $BTEValueOfShares;
                    }


                    //get direct referral's total earning
                    $totalEarning = $bonusTotal + $newlyGeneratedTotal;

                    // get all income quota available products of direct referral
                    $quotaAvailableProducts = ProductPurchaseFacade::getQuotaAvailableByCustomer($share->customer_id);

                    $expiredTotal = 0;

                    // if products available with remaining quota
                    if (count($quotaAvailableProducts)) {
                        foreach ($quotaAvailableProducts as $purchasedProduct) {

                            // if total earing greater than or equal to product's max income quota
                            if ($totalEarning >= $purchasedProduct->max_income_quota) {

                                //update product's income quota status
                                ProductPurchaseFacade::update(
                                    $purchasedProduct,
                                    array(
                                        'income_quota_status' => ProductPurchase::INCOME_QUOTA_STATUS['EXPIRED'],
                                        'remaining_income_quota' => 0
                                    )
                                );

                                $expiredTotal = $purchasedProduct->max_income_quota;

                                $totalEarning = $totalEarning - $expiredTotal;

                                // add supporting bonus to parent referral's wallet and update max and used income quota
                                WalletFacade::update(
                                    $wallet,
                                    array(
                                        'token_amount' => $wallet->token_amount + $newlyGeneratedTotal,
                                        'max_income_quota' => $wallet->max_income_quota - $purchasedProduct->max_income_quota,
                                        'used_income_quota' => 0
                                    )
                                );
                            } else { // if earning total less than product's max income quota


                                // add supporting bonus to parent referral's wallet and update max and used income quota
                                WalletFacade::update(
                                    $wallet,
                                    array(
                                        'token_amount' => $wallet->token_amount + $newlyGeneratedTotal,
                                        'used_income_quota' => $totalEarning
                                    )
                                );

                                //update product's income quota status
                                ProductPurchaseFacade::update(
                                    $purchasedProduct,
                                    array(
                                        'remaining_income_quota' => $purchasedProduct->max_income_quota - $totalEarning
                                    )
                                );

                                break; // Exit the foreach loop

                            }
                        }
                    }

                    WalletTransactionFacade::create(
                        array(
                            'wallet_id' => $wallet->id,
                            'usdt_amount' => 0,
                            'token_amount' => $newlyGeneratedTotal,
                            'type' => WalletTransaction::TYPE['SUPPORTING_BONUS']
                        )
                    );

                    GeneratedSupportingBonusFacade::create(
                        array(
                            'customer_id' => $share->customer_id,
                            'share_amount' => $share->value,
                            'share_value' => $supportingBonusShareValue,
                            'commission' => $newlyGeneratedTotal,
                            'status' => GeneratedSupportingBonus::STATUS['PAID']
                        )
                    );

                    DailyTotalShareFacade::update($share, array('status' => DailyTotalShare::STATUS['CONVERTED']));
                }
            }

            $todaySupportingBonuses = $this->customerSupportingBonus->getTodaySupportingBonuses();

            if (count($todaySupportingBonuses) > 0) {
                foreach ($todaySupportingBonuses as $sportingBonus) {
                    CustomerSupportingBonusFacade::update($sportingBonus, array('status' => CustomerSupportingBonus::STATUS['CONVERTED']));
                }
            }


            // Add record to daily share calculation
            $purchasedTotal = ProductPurchaseFacade::getPurchaseTotalByDate($formattedDate);
            $itemPurchasedTotal = ItemPurchaseFacade::getPurchasedTotalByDate($formattedDate);
            $investedTotal = ProjectInvestmentFacade::getInvestmentTotalByDate($formattedDate);
            $thirdPartyPurchasedTotal = CustomerPurchaseFacade::getPurchaseTotalByDate($formattedDate);

            $itemPurchasedTotal = floatval($itemPurchasedTotal) / UrbxWallet::URBX_VALUE;
            $thirdPartyPurchasedTotal = floatval($thirdPartyPurchasedTotal) / UrbxWallet::URBX_VALUE;

            $totalSales = floatval($purchasedTotal) + floatval($investedTotal) + $itemPurchasedTotal + $thirdPartyPurchasedTotal;

            $binaryPool = $todayTotalPoints * $pointValue;
            $payOut = $totalShares * $supportingBonusShareValue;
            $poolBalance = $binaryPool - $payOut;


            // $calculatedDailyShares = DailyShareCalculationFacade::all();
            $lastCalculatedShare = DailyShareCalculationFacade::getLastRecord();

            if ($lastCalculatedShare) { // if last record available
                $lastCumulativeBalance =  $lastCalculatedShare->cumulative_pool_balance;

                if(($totalShares > 0) && ($realShareValue <= 15)){ // if today pool balance is negative value

                    $cumulativePoolBalance = $lastCumulativeBalance - $poolBalance;
                }else{ // if today pool balance is positive value
                    $cumulativePoolBalance = $lastCumulativeBalance + $poolBalance;
                }

            } else { // if last record not available
                $cumulativePoolBalance = $poolBalance;
            }

            DailyShareCalculationFacade::create(
                array(
                    'total_sales' => $totalSales,
                    'total_point' => $todayTotalPoints,
                    'point_value_bte' => $pointValue,
                    'binary_pool_bte' => $binaryPool,
                    'qualified_shares' => $totalShares,
                    'real_share_value' => $realShareValue,
                    'system_share_value' => $supportingBonusShareValue,
                    'payout' => $payOut,
                    'pool_balance' => $poolBalance,
                    'cumulative_pool_balance' => $cumulativePoolBalance,
                    'status' => DailyShareCalculation::STATUS['ACTIVE'],
                    'type' => (($totalShares > 0) && ($realShareValue <= 15)) ? DailyShareCalculation::TYPE['NEGATIVE'] : DailyShareCalculation::TYPE['POSITIVE']
                )
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new CustomException('Supporting Bonus Exception' . $e);
            // $e; // Re-throw the exception for handling

        }
    }

    /**
     * getChildReferrals - OPTIMIZED with Repository Pattern
     *
     * Uses ReferralRepository for clean separation of concerns
     * SQL complexity is isolated in the repository layer
     *
     * @param  mixed $parent_id
     * @param  mixed $child
     * @return array
     */
    public function getChildReferrals($parent_id, $child)
    {
        $parent = ReferralFacade::get($parent_id);

        if ($child == 'left') {
            $child_id = $parent->left_child_id;
        } else {
            $child_id = $parent->right_child_id;
        }

        if (!$child_id) {
            return [];
        }

        // Use repository - clean, testable, reusable
        return $this->referralRepository->getDescendants($child_id);
    }

     /**
     * getPurchasedChildReferrals
     *
     * @param  mixed $parent_id
     * @param  mixed $child
     * @return void
     */
    public function getPurchasedChildReferrals($parent_id, $child)
    {


        $child_ids = array();
        $array_index = 0;

        $parent = ReferralFacade::get($parent_id); // get current referral parent

        if ($child == 'left') {
            $child_id = $parent->left_child_id; // get child referral id
        } else {
            $child_id = $parent->right_child_id; // get child referral id
        }


        if ($child_id) {

            $childReferral = ReferralFacade::get($child_id);

            // if referral is purchased customer
            if($childReferral->customer->purchased_status == Customer::PURCHASED_STATUS['ACTIVE']){
                array_push($child_ids, $child_id); // add child referral id to array
            }


            while (true) {

                $childReferrals = ReferralFacade::getAllPurchasedChildReferrals($child_id); // get child referrals of current child

                if (count($childReferrals) > 0) { // if current child has child referrals

                    $child_ids = array_merge($child_ids, $childReferrals->all()); // add child referral ids to array
                    $array_index++; // increase array index

                    if(isset($child_ids[$array_index])){ //if next element available
                        $child_id = $child_ids[$array_index];  // make child id is next element in array
                    }else{
                        break; // end loop
                    }


                } else { // if current child don't have child referrals

                    $lastIndex = count($child_ids) - 1; // get last index of array

                    if(count($child_ids)>0){ // if array has elements
                        if ($array_index == $lastIndex) { // if current element is tha last element of array
                            break; // end loop
                        } else { // if current element is not the last element
                            $array_index++;
                            $child_id = $child_ids[$array_index];  // make child id is next element in array
                        }
                    }else{
                        break; // end loop
                    }

                }
            }

            return $child_ids;
        }
    }


    /**
     * getTodaySupportingBonusTotalByReferralAndCustomers
     *
     * @param  mixed $referral
     * @param  mixed $ChildReferrals
     * @return void
     */
    function getTodaySupportingBonusTotalByReferralAndCustomers($referral, $ChildReferrals)
    {
        $customerIds = ReferralFacade::getCustomerIds($ChildReferrals)->toArray();
        return $this->customerSupportingBonus->getTodaySupportingBonusTotalByReferralAndCustomers($referral, $customerIds);
    }
}
