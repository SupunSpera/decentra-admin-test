<?php

namespace App\Http\Controllers;

use App\Models\ConnectedCustomer;
use App\Models\ConnectedProject;
use App\Models\Customer;
use App\Models\CustomerPurchase;
use App\Models\InstituteMember;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\WalletTransaction;
use App\Notifications\CustomerCreated;
use App\Notifications\EmailRecipient;
use App\Traits\Api\ApiHelper;
use domain\Facades\CustomerFacade;
use domain\Facades\Gift\NfcCustomerFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\WalletFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Encrypt\EncryptHelper;
use App\Traits\Referral\ReferralHelper;
use Carbon\Carbon;
use domain\Facades\ConnectedCustomerFacade;
use domain\Facades\ConnectedProjectFacade;
use domain\Facades\CustomerPurchaseFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\DirectReferralBonusFacade;
use domain\Facades\InstituteMemberFacade;
use domain\Facades\ItemDirectCommissionFacade;
use domain\Facades\ProductPurchaseFacade;
use domain\Facades\WalletTransactionFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    use ApiHelper;
    use EncryptHelper;
    use ReferralHelper;

    /**
     * Display the customers
     *
     */
    public function all()
    {
        $activeCustomers = CustomerFacade::getByTypeAndStatus(Customer::TYPE['INDIVIDUAL'], Customer::STATUS['ACTIVE']);
        return view('pages.customers.all', compact('activeCustomers'));
    }

    /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.customers.new');
    }

    /**
     * view
     *
     * @return void
     */
    public function view($id)
    {

        $customer = CustomerFacade::get($id);
        $referral = ReferralFacade::getByCustomerId($customer->id);


        if ($referral && $referral->direct_referral_id) {
            $directReferral =  ReferralFacade::getByCustomerId($referral->direct_referral_id);
            $directReferralCustomer = CustomerFacade::get($directReferral->customer_id);
        } else {
            $directReferralCustomer = null;
        }

        return view('pages.customers.view', compact('customer', 'directReferralCustomer'));
    }

    /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        $customer = CustomerFacade::get($id);
        return view('pages.customers.edit', compact('id', 'customer'));
    }

    /**
     * validateSimulatedSession
     *
     * @param  mixed $hash
     * @return int
     */
    public function validateSimulatedSession(Request $request)
    {

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'session_hash' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $requestData = $request->all();

        $data = NfcCustomerFacade::getBySessionHash($requestData['session_hash']);

        return $this->successResponse($data, Response::HTTP_OK);
    }

    /**
     * wallet
     *
     * @param  mixed $id
     * @return void
     */
    public function wallet($id)
    {
        $wallet = WalletFacade::getByCustomerId($id);
        $customer = CustomerFacade::get($id);
        return view('pages.customers.wallet', compact('id', 'wallet', 'customer'));
    }

    /**
     * referrals
     *
     * @param  mixed $id customer_id
     * @return void
     */
    public function referrals($id)
    {
        $referrals = ReferralFacade::getDirectReferrals($id);
        $customer = CustomerFacade::get($id);
        return view('pages.customers.components.referrals', compact('id', 'referrals', 'customer'));
    }

    /**
     * registerThirdPartyUser
     *
     * @param  mixed $request
     * @return void
     */
    function registerThirdPartyUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|string',
            'referral_id' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Get validated data
        $validatedData = $validator->validated();

        $projectId = $this->custom_decrypt($validatedData['project_id']);

        if (isset($validatedData['referral_id'])) {
            $referralId = $this->custom_decrypt($validatedData['referral_id']);
            $defaultReferral = false;
        } else {
            $referralId = config('nfc_project.third_party_direct_referral');
            $defaultReferral = true;
        }


        $project = ConnectedProjectFacade::get($projectId);

        if ($project && $project->status == ConnectedProject::STATUS['PUBLISHED']) { // if connected project available and project is published

            $referralCustomer = ReferralFacade::getByCustomerId($referralId);

            if ($referralCustomer) {
                $data = array(
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'referral_id' => $referralCustomer->id,
                    'default_referral' => $defaultReferral
                );

                $customer = CustomerFacade::getByEmail($validatedData['email']);

                if ($customer) { // if customer with email already registered
                    $connectedCustomer = ConnectedCustomerFacade::getByCustomerId($customer->id);

                    if ($connectedCustomer) { // if customer already connected
                        return $this->successResponse($customer, Response::HTTP_OK);
                    } else {
                        ConnectedCustomerFacade::create(array(
                            'customer_id' => $customer->id,
                            'project_id' => $project->id
                        ));

                        return $this->successResponse($customer, Response::HTTP_OK);
                    }
                }

                $customer =  $this->autoPlacement($data);
                if ($customer['status'] == 1) {
                    ConnectedCustomerFacade::create(array(
                        'customer_id' => $customer['customer']->id,
                        'project_id' => $project->id
                    ));

                    return $this->successResponse($customer['customer'], Response::HTTP_OK);
                } else {
                    return $this->errorResponse($customer['error'], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            } else {
                return $this->errorResponse('Referral not found!', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            return $this->errorResponse('Project not found!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * validateThirdPartyUser
     *
     * @return void
     */
    function validateThirdPartyUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'ref_code' => 'required|string',

        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Get validated data
        $validatedData = $validator->validated();

        $customer = CustomerFacade::getByRefCode($validatedData['ref_code']);

        if ($customer) {
            $data = array(
                "id" => $customer->id,
                "first_name" => $customer->first_name,
                "last_name" => $customer->last_name,
                "referral_code" => $customer->referral_code,
                "email" => $customer->email,
            );

            return $this->successResponse($data, Response::HTTP_OK);
        } else {
            return $this->errorResponse('Customer not found!', Response::HTTP_UNPROCESSABLE_ENTITY);
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
                return array(
                    'status' => 0,
                    'error' => 'ETH wallet not created!'
                );
            }

            //  send email to customer
            // if($validatedData['default_referral'] == false){ // if not parent referral is default referral
            //     $emailRecipient = new EmailRecipient($validatedData['email']);
            //     Notification::send($emailRecipient, new CustomerCreated($validatedData, $password));
            // }


            // get direct referral customer
            $directReferralCustomer = CustomerFacade::get($parent->customer_id);

            // update direct referral's active status
            CustomerFacade::update(
                $directReferralCustomer,
                array('active_status' => Customer::ACTIVE_STATUS['ACTIVE'])
            );

            DB::commit();

            return array(
                'status' => 1,
                'customer' => $customer
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return array(
                'status' => 0,
                'error' => $e
            );
        }
    }

    /**
     * addPointsThirdPartyUser
     *
     * @param  mixed $request
     * @return void
     */
    public function addPointsThirdPartyUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'connected_id' => 'required|int',
            'points' => 'required|int',
            'amount' => 'required|numeric',
            'project_id' => 'required|int',
            'item_id' => 'required|int'

        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Get validated data
        $validatedData = $validator->validated();

        $referral = ReferralFacade::getByCustomerId($validatedData['connected_id']);

        $tokenAmount = $validatedData['amount'] * Product::TOKEN_RATIO;

        if ($referral) { // update share cap and barrow meter

            // getQuotaAvailableByCustomer
            $purchasedIncomeQuota = $this->getMaxIncomeQuota($tokenAmount);

            $customerPurchase = CustomerPurchaseFacade::create(array(
                'item_id' => $validatedData['item_id'],
                'customer_id' => $referral->customer_id,
                'type' => CustomerPurchase::TYPE['THIRD_PARTY'],
                'amount' => $tokenAmount,
                'points' => $validatedData['points'],
                'max_income_quota' => $purchasedIncomeQuota,
                'remaining_income_quota' => $purchasedIncomeQuota,
                'income_quota_status' => ($purchasedIncomeQuota > 0) ? CustomerPurchase::INCOME_QUOTA_STATUS['AVAILABLE'] : CustomerPurchase::INCOME_QUOTA_STATUS['EXPIRED'],
                'project_id' => $validatedData['project_id'],
            ));

            $purchasedTotal = CustomerPurchaseFacade::getPurchasedTotalByCustomerId($referral->customer_id);
            $availableQuotaTotal = CustomerPurchaseFacade::getTotalAvailableQuotaByCustomer($referral->customer_id);

            $dailyShareCap = $this->getDailyShareCap($purchasedTotal);

            $wallet = WalletFacade::getByCustomerId($referral->customer_id);

            WalletFacade::update(
                $wallet,
                array(

                    'daily_share_cap' => $dailyShareCap,
                    'max_income_quota' => $availableQuotaTotal
                )
            );
        }

        // add customer customer supporting bonus to all it's parent referrals
        if ($referral && $referral->parent_referral_id != null && $referral->direct_referral_id != null) {
            $this->addPointsToParents($referral, $validatedData['points']);
        }

        // add direct referral bonus if customer has direct referral
        if ($referral && $referral->direct_referral_id != null) {
            $tokenAmount = $validatedData['amount'] * Product::TOKEN_RATIO;
            $this->addBonusToDirectReferral($referral, $tokenAmount);
        }

        $data = array(
            'msg' => 'Points added successfully'
        );

        return $this->successResponse($data, Response::HTTP_OK);
    }

    /**
     * getCommissionsThirdPartyUser
     *
     * @param  mixed $request
     * @return void
     */
    function getCommissionsThirdPartyUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'connected_id' => 'required|int'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Get validated data
        $validatedData = $validator->validated();

        $referral = ReferralFacade::getByCustomerId($validatedData['connected_id']);

        if ($referral) { // update share cap and barrow meter
            $commissions = ItemDirectCommissionFacade::getByReferralId($referral->id);

            if($commissions){
                return $this->successResponse($commissions, Response::HTTP_OK);
            }
        }else{
            return $this->errorResponse('Customer not found!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }


    }

    /**
     * addPointsToParents
     *
     * @param  mixed $referral
     * @param  mixed $points
     * @return void
     */
    function addPointsToParents($referral, $points)
    {
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
                'amount' => $points
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
                        'left_points' => $parentReferral->left_points + $points

                    )
                );
            } else if ($side == 'RIGHT') { // if it's right child add points to right side
                ReferralFacade::update(
                    $parentReferral,
                    array(
                        'right_points' => $parentReferral->right_points + $points

                    )
                );
            }

            $previousId = $parent_id;
        }
    }

    /**
     * addBonusToDirectReferral
     *
     * @param  mixed $referral
     * @param  mixed $tokenAmount
     * @return void
     */
    function addBonusToDirectReferral($referral, $tokenAmount)
    {
        $directReferral = ReferralFacade::get($referral->direct_referral_id);
        $directReferralWallet = $directReferral->customer->wallet;
        $directReferralBonusValue = ($tokenAmount * 10) / 100;

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
        $quotaAvailableProducts = CustomerPurchaseFacade::getQuotaAvailableByCustomer($directReferral->customer_id);

        $expiredTotal = 0;

        // if products available with remaining quota
        if (count($quotaAvailableProducts)) {
            foreach ($quotaAvailableProducts as $purchasedProduct) {


                // if total earing greater than or equal to product's max income quota
                if ($totalEarning >= $purchasedProduct->max_income_quota) {

                    //update product's income quota status
                    CustomerPurchaseFacade::update(
                        $purchasedProduct,
                        array(
                            'income_quota_status' => CustomerPurchase::INCOME_QUOTA_STATUS['EXPIRED'],
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
                        CustomerPurchaseFacade::update(
                            $purchasedProduct,
                            array(
                                'remaining_income_quota' => $purchasedProduct->max_income_quota - $totalEarning
                            )
                        );

                        break; // Exit the foreach loop
                    } else { // if no package expired

                        //update product's income quota status
                        CustomerPurchaseFacade::update(
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
                'type' => WalletTransaction::TYPE['ITEM_DIRECT_COMMISSION']
            )
        );

        ItemDirectCommissionFacade::create(
            array(
                'customer_id' => $referral->customer_id,
                'referral_id' => $referral->direct_referral_id,
                'purchased_amount' => $tokenAmount,
                'commission_percentage' => 10,
                'commission_amount' => $newlyGeneratedTotal
            )
        );
    }

    /**
     * getDailyShareCap
     *
     * @param  mixed $purchasedTotal
     * @return void
     */
    function getDailyShareCap($purchasedTotal)
    {
        $dailyShareCap = 0;

        if (1 <= $purchasedTotal && $purchasedTotal < 25000) { // 1 - 24999
            $dailyShareCap = 10;
        } elseif (25000 <= $purchasedTotal && $purchasedTotal <= 250000) {   // 25000 - 250000
            $dailyShareCap = 25;
        } elseif (250000 < $purchasedTotal && $purchasedTotal <= 500000) { // 250001 -500000
            $dailyShareCap = 50;
        } elseif (500000 < $purchasedTotal && $purchasedTotal <= 1000000) {  // 500001 -1000000
            $dailyShareCap = 75;
        } elseif (1000000 < $purchasedTotal) { // 1000001 +
            $dailyShareCap = 100;
        }
        return $dailyShareCap;
    }


    /**
     * getMaxIncomeQuota
     *
     * @param  mixed $productPrice
     * @return Int
     */
    function getMaxIncomeQuota($productPrice): Int
    {
        $maxIncomeQuota = 0;


        if (5000 <= $productPrice && $productPrice <= 10000) { // if invested amount less than or equal 100
            $maxIncomeQuota = $productPrice * 5;
        } else if ($productPrice > 10000) { // if invested amount grater than 100
            $maxIncomeQuota = $productPrice * 4;
        }

        return $maxIncomeQuota;
    }
}
