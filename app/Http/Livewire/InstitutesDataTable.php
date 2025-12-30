<?php

namespace App\Http\Livewire;

use App\Events\MilestoneArchived;
use App\Models\Customer;
use App\Models\InstituteMember;
use domain\Facades\WalletFacade;
use domain\Facades\CustomerFacade;
use domain\Facades\ReferralFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\EmailRecipient;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Referral\ReferralHelper;
use Illuminate\Support\Facades\Session;
use App\Notifications\InstituteApproved;
use Carbon\Carbon;
use domain\Facades\CustomerMilestoneFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\InstituteMemberFacade;
use domain\Facades\ProductPurchaseFacade;
use Illuminate\Support\Facades\Notification;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use App\Traits\Encrypt\EncryptHelper;


class InstitutesDataTable extends LivewireDatatable
{

    use ReferralHelper;
    use EncryptHelper;

    public $frozen_asset;
    public $model = Customer::class;

    /**
     * builder
     *
     * @return void
     */
    public function builder()
    {
        return Customer::query()
            ->where('type', Customer::TYPE['INSTITUTE']);
    }

    protected $listeners = ['approveInstitute'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),

            Column::name('telephone')->label('Telephone'),
            Column::name('mobile')->label('Mobile'),
            Column::name('email')->label('Email'),
            Column::callback(['status'], function ($status) {
                return $this->getStatus($status);
            })->label('Status'),
            Column::raw("DATE_FORMAT(customers.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.institutes.actions', ['id' => $id, 'status' => $status]);
            })->label('Actions'),
        ];
    }

    /**
     * getStatus
     *
     * @param  mixed $type
     * @return string
     */
    public function getStatus($type): string
    {
        $data = '<div class="text-center">';
        if ($type == Customer::STATUS['ACTIVE']) {
            $data = $data . '<span class="badge badge-success">Active</span>';
        } else if ($type == Customer::STATUS['PENDING']) {
            $data = $data . '<span class="badge badge-warning">Pending</span>';
        }
        return $data . '</div>';
    }

    /**
     * approveInstitute
     *
     * @param  mixed $id
     * @return void
     */
    public function approveInstitute($id)
    {
        DB::beginTransaction();
        try {
            $institute = CustomerFacade::get($id);

            if ($institute) {
                CustomerFacade::update($institute, array('status' => Customer::STATUS['ACTIVE'], 'frozen_shares' => $this->frozen_asset));

                //add user as institute principle
                InstituteMemberFacade::create(array(
                    'institute_id' => $institute->id,
                    'customer_id' => $institute->id,
                    'email' => $institute->email,
                    'type' => InstituteMember::TYPE['PRESIDENT'],
                ));

                $ETHWallet = WalletFacade::createETHWallet();

                $data = json_decode($ETHWallet['response']);

                if (isset($data)) { // if wallet created

                    $encryptedPrivateKey = $this->custom_encrypt($data->data->privateKey);

                    WalletFacade::create(array(
                        'customer_id' => $institute->id,
                        'eth_wallet_address' => $data->data->address,
                        'eth_wallet_private_key' => $encryptedPrivateKey
                    )); //create wallet for institute

                    $parentCustomer = CustomerFacade::getByRefCode($institute->direct_ref_code); // get parent customer by ref code

                    $parent = ReferralFacade::getByCustomerId($parentCustomer->id); // get referral

                    if (!$parent->left_child_id) { // if left child of selected referral is empty

                        $levelIndex =  $this->calculateLevelIndex($parent->level_index, 1);

                        $referral_data = array(
                            'customer_id' => $institute->id,
                            'parent_referral_id' => $parent->id,
                            'direct_referral_id' => $parent->id,
                            'level' => ($parent->level + 1),
                            'level_index' => $levelIndex,
                        );

                        $referral = ReferralFacade::create($referral_data);
                        $res = ReferralFacade::update($parent, array('left_child_id' => $referral->id));
                    } else if (!$parent->right_child_id) {  // if right child of selected referral is empty

                        $levelIndex =  $this->calculateLevelIndex($parent->level_index, 2);
                        $referral_data = array(
                            'customer_id' => $institute->id,
                            'parent_referral_id' => $parent->id,
                            'direct_referral_id' => $parent->id,
                            'level' => ($parent->level + 1),
                            'level_index' => $levelIndex,
                        );

                        $referral = ReferralFacade::create($referral_data);
                        $res = ReferralFacade::update($parent, array('right_child_id' => $referral->id));
                    } else { // if selected referral has no empty children

                        $directReferrals = ReferralFacade::getDirectReferrals($parent->id);

                        if (count($directReferrals) == 1) { // only one direct child available under this parent
                            $directReferral = intval($directReferrals[0]->id);
                            $filledReferral = ($directReferral == intval($parent->left_child_id)) ? 'LEFT' : 'RIGHT';
                            if ($filledReferral == 'RIGHT') {
                                // get available outer child in left side
                                $outerChildren = $this->getOuterChildWithSide($parent->id, 'LEFT');
                            } else {
                                // get available outer child in right side
                                $outerChildren = $this->getOuterChildWithSide($parent->id, 'RIGHT');
                            }
                        } else {

                            // get available points of both sides
                            $leftSidePoints = floatval($parent->left_points);
                            $rightSidePoints = floatval($parent->right_points);

                            // get child referral count of both sides
                            $leftReferral = CustomerSupportingBonusFacade::getChildReferrals($parent->id, 'left'); // get all left side child referrals
                            $leftReferralCount = count($leftReferral); // get left side child referrals count

                            $rightReferral = CustomerSupportingBonusFacade::getChildReferrals($parent->id, 'right'); // get all right side child referrals
                            $rightReferralCount = count($rightReferral); // get  right side child referrals count


                            if (($leftSidePoints == 0 && $rightSidePoints == 0) || ($leftReferralCount == 0 && $rightReferralCount == 0)) { // if selected referral has no children or points
                                $outerChildren = $this->getOuterChildren($parent->id);
                            } else if ($leftSidePoints === $rightSidePoints) { // if both side has same points count
                                $outerChildren = $this->getOuterChildren($parent->id);
                            } else { // if customer has points or customers

                                $pointsArray = array(
                                    'leftSide' => floatval($leftSidePoints),
                                    'rightSide' => floatval($rightSidePoints),
                                );

                                $minimumPointsSide = array_search(min($pointsArray), $pointsArray); // Get the key associated with the minimum value

                                //get date before two months
                                $dateBeforeTwoMonths = Carbon::now()->subMonths(2)->format('Y-n-j');

                                // get left side active customers
                                $leftCustomers = ReferralFacade::getCustomerIds($leftReferral);

                                $leftProductPurchasedCustomers = ProductPurchaseFacade::getProductPurchasedCustomersByIds($leftCustomers, $dateBeforeTwoMonths)->toArray();
                                $leftProductPurchasedReferrals = ReferralFacade::getProductPurchasedReferralsByDirectReferralIds($leftReferral, $dateBeforeTwoMonths)->toArray();
                                $leftProductPurchasedParents = ReferralFacade::getCustomerIds($leftProductPurchasedReferrals)->toArray();

                                $leftActiveCustomers = array_unique(array_merge($leftProductPurchasedCustomers, $leftProductPurchasedParents));

                                // get right side active customers
                                $rightCustomers = ReferralFacade::getCustomerIds($rightReferral);

                                $rightProductPurchasedCustomers = ProductPurchaseFacade::getProductPurchasedCustomersByIds($rightCustomers, $dateBeforeTwoMonths)->toArray();
                                $rightProductPurchasedReferrals = ReferralFacade::getProductPurchasedReferralsByDirectReferralIds($rightReferral, $dateBeforeTwoMonths)->toArray();
                                $rightProductPurchasedParents = ReferralFacade::getCustomerIds($rightProductPurchasedReferrals)->toArray();

                                $rightActiveCustomers = array_unique(array_merge($rightProductPurchasedCustomers, $rightProductPurchasedParents));

                                $activeCustomersArray = array(
                                    'leftSide' => count($leftActiveCustomers),
                                    'rightSide' => count($rightActiveCustomers),
                                );

                                $minimumCustomersSide =  array_search(min($activeCustomersArray), $activeCustomersArray); // Get the key associated with the minimum value


                                if ($leftActiveCustomers === $rightActiveCustomers) { // if active user count equal in both sides
                                    $outerChildren = $this->getOuterChildren($parent->id);
                                } else if ($minimumPointsSide === $minimumCustomersSide) { // if minimum points and customer has same side

                                    $side = ($minimumPointsSide === 'leftSide') ? 'LEFT' : 'RIGHT'; // get minimum side
                                    $outerChildren = $this->getOuterChildWithSide($parent->id, $side);
                                } else { // if less points has side which has more customer

                                    $maximumCustomerSide =  array_search(max($activeCustomersArray), $activeCustomersArray); // Get the key associated with the maximum value
                                    $maximumSide = ($maximumCustomerSide === "leftSide") ? 'LEFT' : 'RIGHT';

                                    $leftCustomersCount = $activeCustomersArray['leftSide'];
                                    $rightCustomersCount = $activeCustomersArray['rightSide'];


                                    // get percentage of difference of each side totals
                                    if ($maximumSide === 'RIGHT') { // if right side has more customers

                                        $percentage = ($rightCustomersCount) / 4; // get 25% of right side customers

                                        if ($percentage >= $leftCustomersCount) { // if left side customers less than 25% off right side customers add to more points side

                                            $side = ($minimumPointsSide === 'rightSide') ? 'LEFT' : 'RIGHT'; // get minimum side
                                            $outerChildren = $this->getOuterChildWithSide($parent->id, $side);
                                        } else {  //if left side customers more than 25% of right customers side add to less points side
                                            $side = ($minimumPointsSide === 'rightSide') ? 'RIGHT' : 'LEFT'; // get minimum side
                                            $outerChildren = $this->getOuterChildWithSide($parent->id, $side);
                                        }
                                    } else { // if left side has more customers

                                        $percentage = ($leftCustomersCount) / 4; // get 25% of left side customers

                                        if ($percentage >= $rightCustomersCount) { // if right side total less than 25% off left side total add to more points side
                                            $side = ($minimumPointsSide === 'rightSide') ? 'LEFT' : 'RIGHT'; // get minimum side
                                            $outerChildren = $this->getOuterChildWithSide($parent->id, $side);
                                        } else { //if right side customers more than 25% of left side add to less points side
                                            $side = ($minimumPointsSide === 'rightSide') ? 'RIGHT' : 'LEFT'; // get minimum side
                                            $outerChildren = $this->getOuterChildWithSide($parent->id, $side);
                                        }
                                    }
                                }
                            }
                        }


                        $newParent = ReferralFacade::get($outerChildren['child']);

                        $new_referral_data = array(
                            'customer_id' => $institute->id,
                            'parent_referral_id' => $newParent->id,
                            'direct_referral_id' => $parent->id,
                            'level' => ($newParent->level + 1),
                        );
                        $new_referral = ReferralFacade::create($new_referral_data);

                        if ($outerChildren['side'] == 'left') {
                            $res = ReferralFacade::update($newParent, array('left_child_id' => $new_referral->id));

                            $levelIndex =  $this->calculateLevelIndex($newParent->level_index, 1);
                            $res = ReferralFacade::update($new_referral, array('level_index' => $levelIndex));
                        } else {
                            $res = ReferralFacade::update($newParent, array('right_child_id' => $new_referral->id));

                            $levelIndex =  $this->calculateLevelIndex($newParent->level_index, 2);
                            $res = ReferralFacade::update($new_referral, array('level_index' => $levelIndex));
                        }
                    }
                } else {
                    Session::flash('alert-danger', 'Something went wrong!');
                    return redirect()->route('institutes.all');
                }

                //  send email to institute admin
                $emailRecipient = new EmailRecipient($institute->email);
                Notification::send($emailRecipient, new InstituteApproved($institute));

                // get direct referral customer
                $customer = CustomerFacade::get($parent->id);
                // $archivedMilestones = CustomerMilestoneFacade::archiveMilestonesByCustomer($customer);

                // foreach ($archivedMilestones as $milestone) {
                //     event(new MilestoneArchived($customer, $milestone));
                // }

                // update direct referral's active status
                CustomerFacade::update(
                    $customer,
                    array('active_status' => Customer::ACTIVE_STATUS['ACTIVE'])
                );

                DB::commit();
                Session::flash('alert-success', 'Institute approved successfully');
                return redirect()->route('institutes.all');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Re-throw the exception for handling

            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('institutes.all');
        }
    }
}
