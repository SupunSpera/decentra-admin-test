<?php

namespace domain\Services;

use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\Project;
use App\Models\WalletTransaction;
use domain\Facades\CustomerFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use Illuminate\Database\Eloquent\Collection;

use domain\Facades\ImageFacade;
use domain\Facades\ProductPurchaseFacade;
use domain\Facades\ProjectDirectCommissionFacade;
use domain\Facades\ProjectFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\WalletFacade;
use domain\Facades\WalletTransactionFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ProjectService
{

    protected $project;

    public function __construct()
    {

        $this->project = new Project();
    }
    /**
     * Get project using id
     *
     * @param  int $id
     *
     * @return Project
     */
    public function get(int $id): Project
    {
        return $this->project->find($id);
    }

    /**
     * Get all products
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->project->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->project->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $project
     * @return Project
     */
    public function create(array $project): Project
    {
        return $this->project->create($project);
    }
    /**
     * Update project
     *
     * @param Project $project
     * @param array $data
     *
     *
     */
    public function update(Project $project, array $data)
    {
        return  $project->update($this->edit($project, $data));
    }
    /**
     * Edit project
     *
     * @param Project $project
     * @param array $data
     *
     * @return array
     */
    protected function edit(Project $project, array $data): array
    {
        return array_merge($project->toArray(), $data);
    }
    /**
     * Delete a project
     *
     * @param Project $project
     *
     *
     */
    public function delete(Project $project)
    {
        return $project->delete();
    }

    /**
     * uploadImage
     *
     * @param  mixed $image
     * @return void
     */
    public function uploadImage($image)
    {

        $filename = Str::uuid()->toString() . time() . '.' . $image->getClientOriginalExtension();

        $img = ImageManager::imagick()->read($image);

        $img->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Optionally prevent upsizing
        });

        // Save image to disk
        if (!is_dir(storage_path('app/public/uploads/images/projects/'))) {
            Storage::disk('public')->makeDirectory('/uploads/images/projects/');
        }
        $img->save(storage_path('app/public/uploads/images/projects/') . $filename);

        $imageData = array(
            'name' => $filename
        );
        $image = ImageFacade::make($imageData);
        if ($image) {
            return $image;
        }
    }

    /**
     * getStartedProjectsWithDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getStartedProjectsWithDate($date)
    {
        return $this->project->getStartedProjectsWithDate($date);
    }

    /**
     * completeProjects
     *
     * @return void
     */
    public function completeProjects($date)
    {

        DB::beginTransaction();

        try {

            $completedProjects = ProjectFacade::getStartedProjectsWithDate($date);

            //loop through projects
            foreach ($completedProjects as $project) {

                $investments = $project->projectInvestments;

                //if project harvest type on complete
                if ($project->harvest_type == Project::HARVEST_TYPE['ON_COMPLETE']) {

                    //loop through investments
                    foreach ($investments as $investment) {

                        $investedAmount = floatval($investment->amount);
                        $harvest = $project->harvest;
                        $harvestedAmount = ($investment->amount * $harvest) / 100;
                        $totalReturn = $investedAmount + $harvestedAmount;

                        $customer = CustomerFacade::get($investment->customer_id);
                        $wallet = $customer->wallet;

                        // add return amount to customers wallet
                        WalletFacade::update(
                            $wallet,
                            array(
                                'usdt_amount' => $wallet->usdt_amount + $totalReturn
                            )
                        );

                        //create wallet transaction
                       WalletTransactionFacade::create(
                            array(
                                'wallet_id' => $wallet->id,
                                'token_amount' => 0,
                                'usdt_amount' => $totalReturn,
                                'type' => WalletTransaction::TYPE['PROJECT_HARVEST']
                            )
                        );
                    }
                }

                //if project bonus generation on complete
                if ($project->bonus_generation == Project::BONUS_GENERATION['ON_COMPLETE']) {

                    //loop through investments
                    foreach ($investments as $investment) {

                        $customer = CustomerFacade::get($investment->customer_id);
                        $referral = ReferralFacade::getByCustomerId($customer->id);

                        // add direct referral bonus if customer has direct referral
                        if ($referral->direct_referral_id != null) {

                            $directReferral = ReferralFacade::get($referral->direct_referral_id);
                            $directReferralWallet = $directReferral->customer->wallet;

                            $investedAmount = floatval($investment->amount);
                            $directCommission = $project->direct_commission;
                            $commissionAmount = ($investedAmount * $directCommission) / 100;
                            $directReferralBonusValue = $commissionAmount * Product::TOKEN_RATIO;

                            $bonusTotal = $directReferralWallet->used_income_quota;

                            // get direct referral's remaining income quota amount
                            if ($bonusTotal >=  $directReferralWallet->max_income_quota) {
                                $remainingAmount = 0;
                            } else {
                                $remainingAmount = ($directReferralWallet->max_income_quota) - $bonusTotal;
                            }

                            // get direct referral bonus according to remaining quota
                            if ($remainingAmount < $directReferralBonusValue) {
                                $newlyGeneratedTotal = $remainingAmount;
                            } else {
                                $newlyGeneratedTotal = $directReferralBonusValue;
                            }

                            //get direct referral's total earning
                            $totalEarning = $bonusTotal + $newlyGeneratedTotal;

                            // get all income quota available products of direct referral
                            $quotaAvailableProducts = ProductPurchaseFacade::getQuotaAvailableByCustomer($directReferral->customer_id);

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

                                        // add direct referral bonus to direct referral's wallet and update used income quota
                                        WalletFacade::update(
                                            $directReferralWallet,
                                            array(
                                                'token_amount' => $directReferralWallet->token_amount + $newlyGeneratedTotal,
                                                'max_income_quota' => $directReferralWallet->max_income_quota - $purchasedProduct->max_income_quota,
                                                'used_income_quota' => 0
                                            )
                                        );
                                    } else { // if earning total less than product's max income quota


                                        // if previous package expired
                                        if ($expiredTotal > 0) {

                                            // $remainingTotal = $totalEarning - $expiredTotal;

                                            // add direct referral bonus to direct referral's wallet and update used income quota
                                            WalletFacade::update(
                                                $directReferralWallet,
                                                array(
                                                    'token_amount' => $directReferralWallet->token_amount + $newlyGeneratedTotal,
                                                    // 'max_income_quota' => $purchasedProduct->max_income_quota,
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
                                        } else { // if no package expired

                                            //update product's income quota status
                                            ProductPurchaseFacade::update(
                                                $purchasedProduct,
                                                array(
                                                    'remaining_income_quota' => $purchasedProduct->max_income_quota - $totalEarning
                                                )
                                            );

                                            // add direct referral bonus to direct referral's wallet and update used income quota
                                            WalletFacade::update(
                                                $directReferralWallet,
                                                array(
                                                    'token_amount' => $directReferralWallet->token_amount + $newlyGeneratedTotal,
                                                    'used_income_quota' => $totalEarning
                                                )
                                            );

                                            break; // Exit the foreach loop

                                        }
                                    }
                                }
                            }

                            // add wallet transaction for direct referral bonus
                            WalletTransactionFacade::create(
                                array(
                                    'wallet_id' => $directReferralWallet->id,
                                    'token_amount' => $newlyGeneratedTotal,
                                    'type' => WalletTransaction::TYPE['PROJECT_DIRECT_COMMISSION']
                                )
                            );

                            ProjectDirectCommissionFacade::create(
                                array(
                                    'customer_id' => $customer->id,
                                    'referral_id' => $referral->direct_referral_id,
                                    'invested_amount' => $investedAmount,
                                    'commission_percentage' => $directCommission,
                                    'commission_amount' => $newlyGeneratedTotal
                                )
                            );
                        }



                        // add customer customer supporting bonus to all it's parent referrals
                        if ($referral->parent_referral_id != null && $referral->direct_referral_id != null) {

                            $parent_id = $referral->parent_referral_id;
                            $parent_ids = array();

                            // get all parent referrals upto root parent
                            while (true) {
                                $parent = ReferralFacade::get($parent_id); // get current referral parent

                                if ($parent->parent_referral_id == null) { // if parent id is null (if no parent available)
                                    array_push($parent_ids, $parent->id);
                                    break; // add direct referral to parent_ids array
                                } else { // if  parent is not null
                                    array_push($parent_ids, $parent->id); // add parent referral to parent_ids array
                                    $parent_id = $parent->parent_referral_id;  // go to upper level parent
                                }
                            }


                            $previousId = $referral->id;

                            foreach ($parent_ids as $parent_id) {
                                CustomerSupportingBonusFacade::create(array(
                                    'customer_id' => $referral->customer_id,
                                    'referral_id' => $parent_id,
                                    'amount' => $investment->points
                                ));

                                $parentReferral =  ReferralFacade::get($parent_id); // get referral

                                $side = '';

                                // get child referral's side of parent referral
                                if ($parentReferral->left_child_id == $previousId) {
                                    $side = 'LEFT';
                                } else if ($parentReferral->right_child_id == $previousId) {
                                    $side = 'RIGHT';
                                }

                                if ($side == 'LEFT') { // if it's left child add points to left side
                                    ReferralFacade::update(
                                        $parentReferral,
                                        array(
                                            'left_points' => $parentReferral->left_points + $investment->points

                                        )
                                    );
                                } else if ($side == 'RIGHT') { // if it's right child add points to right side
                                    ReferralFacade::update(
                                        $parentReferral,
                                        array(
                                            'right_points' => $parentReferral->right_points + $investment->points

                                        )
                                    );
                                }

                                $previousId = $parent_id;
                            }
                        }
                    }
                }

                ProjectFacade::update($project,array(
                    'status'=>Project::STATUS['COMPLETED']
                ));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Re-throw the exception for handling
        }
    }
}
