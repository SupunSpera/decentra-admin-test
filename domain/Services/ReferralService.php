<?php

namespace domain\Services;

use App\Models\Referral;
use Illuminate\Database\Eloquent\Collection;

class ReferralService
{

    protected $referral;

    public function __construct()
    {
        $this->referral = new Referral();
    }
    /**
     * Get referral using id
     *
     * @param  int $id
     *
     * @return Referral
     */
    public function get(int $id): Referral
    {
        return $this->referral->find($id);
    }

    /**
     * Get all referrals
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->referral->all();
    }
    /**
     * create
     *
     * @param  mixed $referral
     * @return Referral
     */
    public function create(array $referral): Referral
    {
        return $this->referral->create($referral);
    }
    /**
     * Update referral
     *
     * @param Referral $referral
     * @param array $data
     *
     * @return void
     */
    public function update(Referral $referral, array $data): void
    {
        $referral->update($this->edit($referral, $data));
    }
    /**
     * Edit referral
     *
     * @param Referral $referral
     * @param array $data
     *
     * @return array
     */
    protected function edit(Referral $referral, array $data): array
    {
        return array_merge($referral->toArray(), $data);
    }
    /**
     * Delete a referral
     *
     * @param Referral $referral
     *
     * @return void
     */
    public function delete(Referral $referral): void
    {
        $referral->delete();
    }


    /**
     * getByCustomerId
     *
     * @param  mixed $id
     * @return void
     */
    public function getByCustomerId($id)
    {
        return $this->referral->getByCustomerId($id);
    }

    /**
     * getReferrals
     *
     * @param  mixed $id
     * @return void
     */
    public function getReferrals($id)
    {
        return $this->referral->getReferrals($id);
    }

    /**
     * getReferralsExceptCurrent
     *
     * @param  mixed $id
     * @return void
     */
    public function getReferralsExceptCurrent($id)
    {
        return $this->referral->getReferralsExceptCurrent($id);
    }

    /**
     * getReferralLevels
     *
     * @param  mixed $id
     * @return void
     */
    public function getReferralLevels($level)
    {
        return $this->referral->getReferralLevels($level);
    }

    /**
     * getLeftEmptyReferralByLevel
     *
     * @param  mixed $id
     * @return void
     */
    public function getEmptyReferralByLevel($level, $type)
    {
        return $this->referral->getEmptyReferralByLevel($level, $type);
    }

    /**
     * getAllReferralsByLevels
     *
     * @param  mixed $id
     * @return void
     */
    public function getAllReferralsByLevels()
    {
        return $this->referral->getAllReferralsByLevels();
    }

    /**
     * getAllReferralsByLevelsWithLimit
     *
     * @param  mixed $limit
     * @return void
     */
    public function getAllReferralsByLevelsWithLimit($limit)
    {
        return $this->referral->getAllReferralsByLevelsWithLimit($limit);
    }

    /**
     * getAllReferralsByLevelAndParents
     *
     * @param  mixed $id
     * @return void
     */
    public function getAllReferralsByLevelAndParents($level, $parent_ids)
    {
        return $this->referral->getAllReferralsByLevelAndParents($level, $parent_ids);
    }

    /**
     * getAllReferralIdsByLevelAndParents
     *
     * @param  mixed $id
     * @return void
     */
    public function getAllReferralIdsByLevelAndParents($level, $parent_ids)
    {
        return $this->referral->getAllReferralIdsByLevelAndParents($level, $parent_ids);
    }


    /**
     * getDirectReferralCount
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getDirectReferralCount($referral_id)
    {
        return $this->referral->getDirectReferralCount($referral_id);
    }
    /**
     * getDirectReferrals
     *
     * @param  mixed $referral_id
     * @return mixed
     */
    public function getDirectReferrals($referral_id)
    {
        return $this->referral->getDirectReferrals($referral_id);
    }

    /**
     * getAllChildReferrals
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getAllChildReferrals($referral_id)
    {
        return $this->referral->getAllChildReferrals($referral_id);
    }

     /**
     * getAllPurchasedChildReferrals
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getAllPurchasedChildReferrals($referral_id)
    {
        return $this->referral->getAllPurchasedChildReferrals($referral_id);
    }

    /**
     * getCustomerById
     *
     * @param  mixed $referral_id
     * @return void
     */
    public function getCustomerById($referral_id)
    {
        return $this->referral->getCustomerById($referral_id);
    }

    /**
     * getCustomerIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    public function getCustomerIds($referralIds)
    {
        return $this->referral->getCustomerIds($referralIds);
    }

    /**
     * getCustomerIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    public function getReferralIds($customerIds)
    {
        return $this->referral->getReferralIds($customerIds);
    }

    /**
     * getDirectReferralIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    public function getDirectReferralIds($referralIds)
    {
        return $this->referral->getDirectReferralIds($referralIds);
    }


    /**
     * getProductPurchasedReferralsByDirectReferralIds
     *
     * @param  mixed $referralIds
     * @return void
     */
    public function getProductPurchasedReferralsByDirectReferralIds($referralIds,$date)
    {
        return $this->referral->getProductPurchasedReferralsByDirectReferralIds($referralIds,$date);
    }


    /**
     * getMaxLevel
     *
     * @return void
     */
    public function getMaxLevel()
    {
        return $this->referral->getMaxLevel();
    }
}
