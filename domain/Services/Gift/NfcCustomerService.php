<?php

namespace domain\Services\Gift;

use App\Models\Gift\NfcCustomer;
use domain\Facades\CustomerFacade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class NfcCustomerService
{


    protected $nfcCustomer;

    public function __construct()
    {
        $this->nfcCustomer = new NfcCustomer();
    }
    /**
     * Get nfcCustomer using id
     *
     * @param  int $id
     *
     * @return NfcCustomer
     */
    public function get(int $id): NfcCustomer
    {
        return $this->nfcCustomer->find($id);
    }
    /**
     * checkCustomerCompletedNFCChallenge
     *
     * @param  mixed $customer
     * @return void
     */
    public function checkCustomerCompletedNFCChallenge($customer)
    {
        //need to code gift completed logic
        return true;
    }
    /**
     * Get all nfcCustomer
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->nfcCustomer->all();
    }
    /**
     * create
     *
     * @param  mixed $nfcCustomer
     * @return NfcCustomer
     */
    public function create(array $nfcCustomer): NfcCustomer
    {
        return $this->nfcCustomer->create($nfcCustomer);
    }

    /**
     * update
     *
     * @param  mixed $nfcCustomer
     * @param  mixed $data
     *
     */
    public function update(NfcCustomer $nfcCustomer, array $data)
    {
        return $nfcCustomer->update($this->edit($nfcCustomer, $data));
    }
    /**
     * Edit nfcCustomer
     *
     * @param NfcCustomer $nfcCustomer
     * @param array $data
     *
     * @return array
     */
    protected function edit(NfcCustomer $nfcCustomer, array $data): array
    {
        return array_merge($nfcCustomer->toArray(), $data);
    }
    /**
     * Delete a nfcCustomer
     *
     * @param NfcCustomer $nfcCustomer
     *
     * @return void
     */
    public function delete(NfcCustomer $nfcCustomer): void
    {
        $nfcCustomer->delete();
    }
    /**
     * makeNfcCardCustomers
     *
     * @return void
     */
    public function makeNfcCardCustomers()
    {
        //get already nfc activated customer ids
        $activatedCustomerIds = $this->getNfcActivatedCustomersIds();
        //get other customers
        $customers = CustomerFacade::getNfcCardNotActivatedCustomers($activatedCustomerIds);

        $customerData = array();
        foreach ($customers as $customer) {
            //check customer completed gift challenge
            if ($this->checkCustomerCompletedNFCChallenge($customer) == true) {
                //create nfc customer record
                $nfcCustomer = $this->create([
                    'customer_id' => $customer->id,
                    'card_status' => NfcCustomer::CARD_STATUS['PENDING'],
                    'purchase_status' => NfcCustomer::PURCHASE_STATUS['NO'],
                    'status' => NfcCustomer::STATUS['PENDING'],
                ]);
                $customerData[] = [
                    'customer_id' => $customer->id,
                    'nfc_customer_id' => $nfcCustomer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                ];
            }
        }

        //send customer data to nfc project
        if ($customerData != []) {
            $response = Http::withHeaders([
                'Authorization' => config('nfc_project.api_key'),
            ])->post(config('nfc_project.admin_path') . 'api/customers/create', $customerData);

            if ($response->successful()) {
                $response = $response->json();
                if ($response['status'] == 'success') {
                    $nfcCustomers = $response['data'];
                    foreach ($nfcCustomers as $nfcCustomerData) {
                        if (isset($nfcCustomerData['nfc_customer_id']) && isset($nfcCustomerData['nfc_connected_id'])) {
                            $this->update(
                                $this->get($nfcCustomerData['nfc_customer_id']),
                                [
                                    'nfc_connected_id' => $nfcCustomerData['nfc_connected_id'],
                                    'purchase_status' => NfcCustomer::PURCHASE_STATUS['YES'],
                                    'status' => NfcCustomer::STATUS['ACTIVE'],
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
    /**
     * getNfcActivatedCustomersIds
     *
     * @return void
     */
    public function getNfcActivatedCustomersIds()
    {
        return $this->nfcCustomer->getNfcActivatedCustomersIds();
    }

    /**
     * getBySessionHash
     *
     * @param  mixed $hash
     * @return void
     */
    public function getBySessionHash($hash){
        return $this->nfcCustomer->getBySessionHash($hash);

    }
}
