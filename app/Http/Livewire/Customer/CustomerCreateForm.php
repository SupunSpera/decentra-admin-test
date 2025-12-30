<?php

namespace App\Http\Livewire\Customer;

use App\Events\MilestoneArchived;
use App\Models\Customer;
use App\Models\InstituteMember;
use App\Notifications\CustomerCreated;
use App\Notifications\EmailRecipient;
use App\Notifications\MilestoneAchieved;
use App\Traits\Referral\ReferralHelper;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
use domain\Facades\CustomerMilestoneFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\InstituteMemberFacade;
use domain\Facades\ProductPurchaseFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\WalletFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Traits\Encrypt\EncryptHelper;

class CustomerCreateForm extends Component
{
    use ReferralHelper;
    use EncryptHelper;

    public $first_name, $last_name, $email, $referral_id, $password, $confirmPassword, $parentReferral, $placement;
    public $referrals, $ParentChildren;

    public function render()
    {
        return view('pages.customers.components.create-form');
    }

    public function mount()
    {
        $this->referrals = ReferralFacade::all();
        $this->ParentChildren = array();
        $this->parentReferral = 0;
        $this->placement = 0;
    }

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:120|unique:customers',
            'referral_id' => 'required',
            'parentReferral' => 'required',
            'placement' => 'required',
            // 'password' => ['required', 'min:8', 'same:confirmPassword'],
        ];
    }
    protected $messages = [
        'first_name.required' => 'Please Enter First Name',
        'last_name.required' => 'Please Enter Last Name',
        'email.required' => 'Please Enter Email Address',
        'referral_id.required' => 'Please Select Referral',
        // 'password.required' => 'Please Enter Password',
    ];

    // Real-time validation using the updated() hook
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * submit
     *
     * @return void
     */
    public function submit()
    {
        $validatedData = $this->validate();

        if ($validatedData['parentReferral'] != 0 && $validatedData['placement'] != 0) {
            $this->manualPlacement($validatedData);
        } else {
            $this->autoPlacement($validatedData);
        }
    }

    // Method to reset form fields
    public function clearForm()
    {
        $this->reset(['first_name', 'last_name', 'email', 'referral_id', 'password', 'confirmPassword']);
    }

    /**
     * getParentPlacement
     *
     * @return void
     */
    function getParentPlacement()
    {
        if ($this->parentReferral != 0) {
            $parentData =  ReferralFacade::get($this->parentReferral);
            $childArray = array();

            if ($parentData->left_child_id == null) {
                $childArray['L'] = 'Left';
            }
            if ($parentData->right_child_id == null) {
                $childArray['R'] = 'Right';
            }


            $this->ParentChildren = $childArray;
        } else {
            $this->ParentChildren = array();
        }
    }

    /**
     * manualPlacement
     *
     * @return void
     */
    function manualPlacement($validatedData)
    {


        DB::beginTransaction();

        try {

            $ETHWallet = WalletFacade::createETHWallet();

            $data = json_decode($ETHWallet['response']);
            if (isset($data)) { // if wallet created

                $encryptedPrivateKey = $this->custom_encrypt($data->data->privateKey);

                $password = Str::random(8);

                $customer = CustomerFacade::create(array(
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'status' => Customer::STATUS['ACTIVE'],
                    'type' => Customer::TYPE['INDIVIDUAL'],
                    'password' => Hash::make($password),
                ));

                if ($customer) {
                    WalletFacade::create(array(
                        'customer_id' => $customer->id,
                        'eth_wallet_address' => $data->data->address,
                        'eth_wallet_private_key' => $encryptedPrivateKey
                    )); //create wallet for customer

                    // check customer is available pending status in institute members table
                    $member = InstituteMemberFacade::findMemberInPendingStatus($customer->email);

                    if ($member) { // if member found

                        $member = InstituteMemberFacade::update(
                            $member,
                            array(
                                'customer_id' => $customer->id,
                                'status' => InstituteMember::STATUS['ACTIVE'],

                            )
                        );
                    }

                    $directReferralId = $validatedData['referral_id'];
                    $parentReferralId = $validatedData['parentReferral'];
                    $placement = $validatedData['placement'];

                    $parent = ReferralFacade::get($parentReferralId); // selected parent

                    if ($placement == 'L') { // if placement is left

                        $levelIndex = $this->calculateLevelIndex($parent->level_index, 1);

                        $referral_data = array(
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $parentReferralId,
                            'direct_referral_id' => $directReferralId,
                            'level' => ($parent->level + 1),
                            'level_index' => $levelIndex,
                        );

                        $referral = ReferralFacade::create($referral_data);
                        $res = ReferralFacade::update($parent, array('left_child_id' => $referral->id));
                    } else if ($placement == 'R') { // if placement is right

                        $levelIndex = $this->calculateLevelIndex($parent->level_index, 2);
                        $referral_data = array(
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $parentReferralId,
                            'direct_referral_id' => $directReferralId,
                            'level' => ($parent->level + 1),
                            'level_index' => $levelIndex,
                        );

                        $referral = ReferralFacade::create($referral_data);

                        $res = ReferralFacade::update($parent, array('right_child_id' => $referral->id));
                    }

                    //  send email to customer
                    $emailRecipient = new EmailRecipient($validatedData['email']);
                    Notification::send($emailRecipient, new CustomerCreated($validatedData, $password));

                    // get direct referral customer
                    $directReferralCustomer = CustomerFacade::get($directReferralId);

                    // update direct referral's active status
                    CustomerFacade::update(
                        $directReferralCustomer,
                        array('active_status' => Customer::ACTIVE_STATUS['ACTIVE'])
                    );
                }
            }


            DB::commit();
            Session::flash('alert-success', 'Customer created successfully');
            return redirect()->route('customers.all');
        } catch (\Exception $e) {

            DB::rollBack();
            // throw $e; // Re-throw the exception for handling

            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('customers.all');
        }
    }

    /**
     * autoPlacement
     *
     * @return void
     */
    function autoPlacement($validatedData)
    {

        DB::beginTransaction();

        try {

            $ETHWallet = WalletFacade::createETHWallet();

            $data = json_decode($ETHWallet['response']);

            if (isset($data)) { // if wallet created

                $encryptedPrivateKey = $this->custom_encrypt($data->data->privateKey);

                $password = Str::random(8);

                $customer = CustomerFacade::create(array(
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'status' => Customer::STATUS['ACTIVE'],
                    'type' => Customer::TYPE['INDIVIDUAL'],
                    'password' => Hash::make($password),
                ));

                if ($customer) {
                    WalletFacade::create(array(
                        'customer_id' => $customer->id,
                        'eth_wallet_address' => $data->data->address,
                        'eth_wallet_private_key' => $encryptedPrivateKey
                    )); //create wallet for customer

                    // check customer is available pending status in institute members table
                    $member = InstituteMemberFacade::findMemberInPendingStatus($customer->email);

                    if ($member) { // if member found

                        $member = InstituteMemberFacade::update(
                            $member,
                            array(
                                'customer_id' => $customer->id,
                                'status' => InstituteMember::STATUS['ACTIVE'],

                            )
                        );
                    }

                    $parent = ReferralFacade::get($validatedData['referral_id']); // selected referral

                    if (!$parent->left_child_id) { // if left child of selected referral is empty

                        $levelIndex = $this->calculateLevelIndex($parent->level_index, 1);

                        $referral_data = array(
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $parent->id,
                            'direct_referral_id' => $parent->id,
                            'level' => ($parent->level + 1),
                            'level_index' => $levelIndex,
                        );

                        $referral = ReferralFacade::create($referral_data);
                        $res = ReferralFacade::update($parent, array('left_child_id' => $referral->id));
                    } else if (!$parent->right_child_id) { // if right child of selected referral is empty

                        $levelIndex = $this->calculateLevelIndex($parent->level_index, 2);
                        $referral_data = array(
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $parent->id,
                            'direct_referral_id' => $parent->id,
                            'level' => ($parent->level + 1),
                            'level_index' => $levelIndex,
                        );

                        $referral = ReferralFacade::create($referral_data);

                        $res = ReferralFacade::update($parent, array('right_child_id' => $referral->id));
                    } else { // if selected referral has no empty children

                        $directReferrals = ReferralFacade::getDirectReferrals($validatedData['referral_id']);

                        if (count($directReferrals) == 1) { // only one direct child available under this parent
                            $directReferral = intval($directReferrals[0]->id);
                            $filledReferral = ($directReferral == intval($parent->left_child_id)) ? 'LEFT' : 'RIGHT';
                            if ($filledReferral == 'RIGHT') {
                                // get available outer child in left side
                                $outerChildren = $this->getOuterChildWithSide($validatedData['referral_id'], 'LEFT');
                            } else {
                                // get available outer child in right side
                                $outerChildren = $this->getOuterChildWithSide($validatedData['referral_id'], 'RIGHT');
                            }
                        } else { // if customer has more than 1 direct referral
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
                                    $outerChildren = $this->getOuterChildWithSide($validatedData['referral_id'], $side);
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

                        // $newParent = $this->findNextAvailableChild($parent->level);

                        $new_referral_data = array(
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $newParent->id,
                            'direct_referral_id' => $parent->id,
                            'level' => ($newParent->level + 1),
                        );
                        $new_referral = ReferralFacade::create($new_referral_data);

                        if ($outerChildren['side'] == 'left') {
                            $res = ReferralFacade::update($newParent, array('left_child_id' => $new_referral->id));

                            $levelIndex = $this->calculateLevelIndex($newParent->level_index, 1);
                            $res = ReferralFacade::update($new_referral, array('level_index' => $levelIndex));
                        } else {
                            $res = ReferralFacade::update($newParent, array('right_child_id' => $new_referral->id));

                            $levelIndex = $this->calculateLevelIndex($newParent->level_index, 2);
                            $res = ReferralFacade::update($new_referral, array('level_index' => $levelIndex));
                        }
                    }
                }
            } else {
                Session::flash('alert-danger', 'Something went wrong!');
                return redirect()->route('customers.all');
            }

            //  send email to customer
            $emailRecipient = new EmailRecipient($validatedData['email']);
            Notification::send($emailRecipient, new CustomerCreated($validatedData, $password));

            // get direct referral customer
            $directReferralCustomer = CustomerFacade::get($parent->customer_id);

            // $archivedMilestones = CustomerMilestoneFacade::archiveMilestonesByCustomer($directReferralCustomer);

            // foreach ($archivedMilestones as $milestone) {
            //     event(new MilestoneArchived($directReferralCustomer, $milestone));
            // }

            // update direct referral's active status
            CustomerFacade::update(
                $directReferralCustomer,
                array('active_status' => Customer::ACTIVE_STATUS['ACTIVE'])
            );

            DB::commit();
            Session::flash('alert-success', 'Customer created successfully');
            return redirect()->route('customers.all');
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e; // Re-throw the exception for handling

            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('customers.all');
        }
    }
}
