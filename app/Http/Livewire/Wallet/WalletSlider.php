<?php

namespace App\Http\Livewire\Wallet;

use App\Models\Customer;
use App\Models\InstituteMember;
use App\Models\WalletRedeem;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use DateTime;
use domain\Facades\CurrencyPoolFacade;
use domain\Facades\CustomerFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\DirectReferralBonusFacade;
use domain\Facades\FrozenTokenFacade;
use domain\Facades\InstituteMemberFacade;
use domain\Facades\InstituteWithdrawalApprovalFacade;
use domain\Facades\InstitutionalBonusFacade;
use domain\Facades\InvestmentFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\SupportingBonusFacade;
use domain\Facades\TokenValueFacade;
use domain\Facades\WalletDepositFacade;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Facades\WalletTransactionFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WalletSlider extends Component
{
    public $wallet, $customer_id;
    public $walletAddress, $texValue,  $amount;
    public $type;

    protected $listeners = ['setType'];

    public function mount()
    {

        $this->wallet = WalletFacade::getByCustomerId($this->customer_id);
        $currencyPool = CurrencyPoolFacade::getFirst();
        if (floatval($currencyPool->tex_amount) > 0) {
            $this->texValue = floatval($currencyPool->usdt_amount) / floatval($currencyPool->tex_amount);
        } else {
            // Handle division by zero
            $this->texValue = 0; // Or any other desired default value
            // Or throw an exception: throw new Exception("Division by zero");
            // Or log an error: error_log("Division by zero encountered");
        }
    }

    public function render()
    {
        return view('pages.customers.components.wallet-slider');
    }

    protected function rules()
    {
        $rules = [];
        $rules['amount'] = 'required|numeric|min:1';
        return $rules;
    }


    /**
     * submit
     *
     * @return void
     */
    function submit()
    {


        $validatedData = $this->validate();

        DB::beginTransaction();

        try {

            if ($this->type == "USDT") {

                // calculate BTE value
                $currencyPool = CurrencyPoolFacade::getFirst();

                $usdtAmount = floatval($currencyPool->usdt_amount);
                $texAmount = floatval($currencyPool->tex_amount);

                if (floatval($texAmount) > 0) {
                    $texValue = $usdtAmount / $texAmount;

                    $depositedAmount = $validatedData['amount'];

                    // calculate BTE amount based on BTE value
                    $depositedBTEAmount = floatval($depositedAmount) / $texValue;
                } else {
                    $texValue = 0;
                    $depositedBTEAmount = 0;
                }

                // add deposit amount to customers wallet
                $walletUpdate = WalletFacade::update(
                    $this->wallet,
                    array(
                        'deposited_token_amount' => $this->wallet->deposited_token_amount + $depositedBTEAmount,
                        'usdt_amount' => $this->wallet->usdt_amount + $depositedAmount
                    )
                );

                if ($walletUpdate) {

                    // save current BTE value
                    TokenValueFacade::create(array('token_value' => $texValue));

                    // add usdt amount and reduce BTE amount from pool
                    // CurrencyPoolFacade::update($currencyPool, array(
                    //     'usdt_amount' => $usdtAmount + $depositedAmount,
                    //     'tex_amount' => $texAmount - $depositedBTEAmount
                    // ));

                    // create wallet transaction
                    $walletTransaction = WalletTransactionFacade::create(
                        array(
                            'wallet_id' => $this->wallet->id,
                            'token_amount' => 0,
                            'usdt_amount' => $depositedAmount,
                            'from' => WalletTransaction::FROM['ADMIN']
                        )
                    );

                    $referral = ReferralFacade::getByCustomerId($this->wallet->customer_id);
                }
            } else {

                $customer = CustomerFacade::get($this->customer_id);
                $depositedAmount = $validatedData['amount'];

                if ($customer) {
                    CustomerFacade::update($customer, array('frozen_shares' => $customer->frozen_shares + $depositedAmount ));
                    FrozenTokenFacade::create(array('wallet_id'=>$this->wallet->id,'token_amount'=>$depositedAmount));

                }

            }


            DB::commit();
            $this->dispatchBrowserEvent('deposit-success');
            $this->emitTo('wallet.wallet-transaction-data-table', 'refresh');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $this->dispatchBrowserEvent('went-wrong');
        }
    }

    /**
     * setType
     *
     * @param  mixed $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
