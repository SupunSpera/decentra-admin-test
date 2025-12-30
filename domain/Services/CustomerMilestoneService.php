<?php

namespace domain\Services;

use App\Events\MilestoneArchived;
use App\Models\CustomerMilestone;
use App\Models\Milestone;
use domain\Facades\CustomerFacade;
use domain\Facades\CustomerMilestoneFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\MilestoneFacade;
use domain\Facades\ProductPurchaseFacade;
use domain\Facades\ReferralFacade;
use Illuminate\Database\Eloquent\Collection;

class CustomerMilestoneService
{

    protected $customerMilestone;

    public function __construct()
    {
        $this->customerMilestone = new CustomerMilestone();
    }
    /**
     * Get customerMilestone using id
     *
     * @param  int $id
     *
     * @return CustomerMilestone
     */
    public function get(int $id): CustomerMilestone
    {
        return $this->customerMilestone->find($id);
    }

    /**
     * Get all customerMilestone
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->customerMilestone->all();
    }


    /**
     * getPublished
     *
     * @return Collection
     */
    public function getPublished(): Collection
    {
        return $this->customerMilestone->getPublished();
    }

    /**
     * create
     *
     * @param  mixed $customerMilestone
     * @return CustomerMilestone
     */
    public function create(array $customerMilestone): CustomerMilestone
    {
        return $this->customerMilestone->create($customerMilestone);
    }
    /**
     * Update customerMilestone
     *
     * @param CustomerMilestone $customerMilestone
     * @param array $data
     *
     *
     */
    public function update(CustomerMilestone $customerMilestone, array $data)
    {
        return  $customerMilestone->update($this->edit($customerMilestone, $data));
    }
    /**
     * Edit customerMilestone
     *
     * @param mMilestone $customerMilestone
     * @param array $data
     *
     * @return array
     */
    protected function edit(CustomerMilestone $customerMilestone, array $data): array
    {
        return array_merge($customerMilestone->toArray(), $data);
    }
    /**
     * Delete a customerMilestone
     *
     * @param CustomerMilestone $customerMilestone
     *
     *
     */
    public function delete(CustomerMilestone $customerMilestone)
    {
        return $customerMilestone->delete();
    }


    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->customerMilestone->getByCustomerId($id);
    }

    /**
     * getArchivedCustomersIds
     *
     * @param  mixed $id
     * @return void
     */
    public function getArchivedCustomersIds($id)
    {
        return $this->customerMilestone->getArchivedCustomersIds($id);
    }

    /**
     * getArchivedIdsByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getArchivedIdsByCustomerId($id)
    {
        return $this->customerMilestone->getArchivedIdsByCustomerId($id);
    }

    /**
     * archiveMilestonesByCustomer
     *
     * @return void
     */
    public function archiveMilestonesByCustomer($customer)
    {
        $archivedMilestones = $this->customerMilestone->getArchivedIdsByCustomerId($customer->id)->toArray();
        $unArchivedMilestones = MilestoneFacade::getIdsExceptGiven($archivedMilestones);

        $newMilestones = array();

        foreach ($unArchivedMilestones as $milestone) {


            if ($milestone->type == Milestone::TYPE['DIRECT_REFERRAL']) { // if milestone type direct referral

                $referral = ReferralFacade::getByCustomerId($customer->id);

                if($referral){

                    $directReferralCount = ProductPurchaseFacade::getPurchasedReferralsByDirectReferral($referral->id);

                    if (count($directReferralCount) >= $milestone->count) { // if customer's direct referral count grater than or equal milestone's direct referral count

                        // add customer to archived list
                        CustomerMilestoneFacade::create(
                            array(
                                'customer_id' => $customer->id,
                                'milestone_id' => $milestone->id,
                                'status' => CustomerMilestone::STATUS["ACTIVE"]
                            )
                        );

                        array_push($newMilestones, $milestone);
                    }
                }
            } else if ($milestone->type == Milestone::TYPE['CLIENT_BASE']) { // if milestone type client base


                $referral = ReferralFacade::getByCustomerId($customer->id);

                $leftReferrals = CustomerSupportingBonusFacade::getChildReferrals($referral->id, 'left');
                $leftReferrals =  is_array($leftReferrals) ? count($leftReferrals) : 0;

                $rightReferrals = CustomerSupportingBonusFacade::getChildReferrals($referral->id, 'right');
                $rightReferrals =  is_array($rightReferrals) ? count($rightReferrals) : 0;

                $totalReferrals = $leftReferrals + $rightReferrals;

                if ($totalReferrals >= $milestone->count) { // if customer's client base grater than or equal milestone's client base

                    // add customer to archived list
                    CustomerMilestoneFacade::create(
                        array(
                            'customer_id' => $customer->id,
                            'milestone_id' => $milestone->id,
                            'status' => CustomerMilestone::STATUS["ACTIVE"]
                        )
                    );

                    array_push($newMilestones, $milestone);
                }
            }
        }

        return $newMilestones;
    }

    /**
     * archiveMilestones
     *
     * @return void
     */
    public function archiveMilestones()
    {
        $milestones = MilestoneFacade::getPublished();

        foreach ($milestones as $milestone) {

            $archivedCustomers = $this->customerMilestone->getArchivedCustomersIds($milestone->id)->toArray();

            $unArchivedCustomers = CustomerFacade::getCustomerIdsExceptGiven($archivedCustomers);


            if ($milestone->type == Milestone::TYPE['DIRECT_REFERRAL']) { // if milestone type direct referral

                foreach ($unArchivedCustomers as $customer) {

                    $referral = ReferralFacade::getByCustomerId($customer);

                    $directReferralCount = ProductPurchaseFacade::getPurchasedReferralsByDirectReferral($referral->id);


                    if (count($directReferralCount) >= $milestone->count) { // if customer's direct referral count grater than or equal milestone's direct referral count

                        // add customer to archived list
                        CustomerMilestoneFacade::create(
                            array(
                                'customer_id' => $customer,
                                'milestone_id' => $milestone->id,
                                'status' => CustomerMilestone::STATUS["ACTIVE"]
                            )
                        );

                        // send email for achieved milestone
                        $customer = CustomerFacade::get($customer);
                        event(new MilestoneArchived($customer, $milestone));
                    }
                }
            } else if ($milestone->type == Milestone::TYPE['CLIENT_BASE']) { // if milestone type client base

                foreach ($unArchivedCustomers as $customer) {

                    $referral = ReferralFacade::getByCustomerId($customer);

                    $leftReferrals = CustomerSupportingBonusFacade::getPurchasedChildReferrals($referral->id, 'left');
                    $leftReferrals =  is_array($leftReferrals) ? count($leftReferrals) : 0;

                    $rightReferrals = CustomerSupportingBonusFacade::getPurchasedChildReferrals($referral->id, 'right');
                    $rightReferrals =  is_array($rightReferrals) ? count($rightReferrals) : 0;

                    $totalReferrals = $leftReferrals + $rightReferrals;

                    if ($totalReferrals >= $milestone->count) { // if customer's client base grater than or equal milestone's client base

                        // add customer to archived list
                        CustomerMilestoneFacade::create(
                            array(
                                'customer_id' => $customer,
                                'milestone_id' => $milestone->id,
                                'status' => CustomerMilestone::STATUS["ACTIVE"]
                            )
                        );

                         // send email for achieved milestone
                         $customer = CustomerFacade::get($customer);
                         event(new MilestoneArchived($customer, $milestone));
                    }
                }
            }
        }
    }
}
