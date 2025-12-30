<?php

namespace App\Http\Livewire;

use domain\Facades\CurrencyPoolFacade;
use domain\Facades\SettingFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SettingUpdateForm extends Component
{
    public $setting, $currencyPool, $daily_income_cap, $inp_tex_amount, $inp_share_value, $usd_to_bte_fee, $bte_to_usd_fee, $withdrawal_fee;
    public $unhold_period,$unfreeze_period,$unhold_period_type,$unfreeze_period_type;

    public function mount()
    {
        $this->setting = SettingFacade::getFirstRecord();
        $this->currencyPool = CurrencyPoolFacade::getFirst();
        // $this->daily_income_cap = (isset($this->setting->daily_income_cap)) ? $this->setting->daily_income_cap : 0;
        $this->inp_share_value = (isset($this->setting->share_value)) ? $this->setting->share_value : 0;
        $this->inp_tex_amount = (isset($this->currencyPool->tex_amount)) ? $this->currencyPool->tex_amount : 0;
        $this->usd_to_bte_fee = (isset($this->setting->usd_to_bte_fee)) ? $this->setting->usd_to_bte_fee : 0;
        $this->bte_to_usd_fee = (isset($this->setting->bte_to_usd_fee)) ? $this->setting->bte_to_usd_fee : 0;
        $this->withdrawal_fee = (isset($this->setting->withdrawal_fee)) ? $this->setting->withdrawal_fee : 0;
        $this->unhold_period = (isset($this->setting->unhold_period)) ? $this->setting->unhold_period : '';
        $this->unfreeze_period = (isset($this->setting->unfreeze_period)) ? $this->setting->unfreeze_period : '';
        $this->unhold_period_type = 1;
        $this->unfreeze_period_type =1;
    }
    public function render()
    {
        return view('pages.settings.components.setting-update-form');
    }

    protected function rules()
    {
        $rules = [
            // 'daily_income_cap' => 'required|numeric|min:1',
            'inp_tex_amount' => 'required|numeric|min:1',
            'inp_share_value' => 'required|numeric|min:1',
            'usd_to_bte_fee' => 'required|numeric|min:0.1',
            'bte_to_usd_fee' => 'required|numeric|min:0.1',
            'withdrawal_fee' => 'required|numeric|min:0.1',
            'unhold_period' => 'required|min:0',
            'unfreeze_period' => 'required|min:0'

        ];

        return $rules;
    }

    protected $messages = [
        // 'daily_income_cap.required' => 'Please Enter Daily Income Cap',
        // 'daily_income_cap.numeric' => 'Daily Income Cap should be a numeric value',
        'inp_tex_amount.required' => 'Please Enter URBX Amount',
        'inp_tex_amount.numeric' => 'URBX Amount should be a numeric value',
        'inp_tex_amount.min' => 'Minimum URBX Amount should be 1',
        'inp_share_value.required' => 'Please Enter Share Value',
        'inp_share_value.numeric' => 'Share Value should be a numeric value',
        'inp_share_value.min' => 'Minimum Share Value should be 1',
        'usd_to_bte_fee.required' => 'Please Enter Usd To Bte Swap Fee Value',
        'usd_to_bte_fee.numeric' => 'Usd To Bte Swap Fee Value should be a numeric value',
        'usd_to_bte_fee.min' => 'Minimum Usd To Bte Swap Fee Value should be 1',
        'bte_to_usd_fee.required' => 'Please Enter Bte To Usd Swap Fee Value',
        'bte_to_usd_fee.numeric' => 'Bte To Usd Swap Fee Value should be a numeric value',
        'bte_to_usd_fee.min' => 'Minimum Bte To Usd Swap Fee Value should be 1',
        'withdrawal_fee.required' => 'Please Enter Withdrawal Fee Value',
        'withdrawal_fee.numeric' => 'Withdrawal Fee Value should be a numeric value',
        'withdrawal_fee.min' => 'Minimum Withdrawal Fee Value should be 1',
        'unhold_period.required' => 'Please Enter Unhold Period',
        'unfreeze_period.required' => 'Please Enter Unfreeze Period',
    ];

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


        $unholdPeriod = $validatedData['unhold_period'] * $this->unhold_period_type;
        $unfreezePeriod = $validatedData['unfreeze_period'] * $this->unfreeze_period_type;

        if ($this->setting) {
            $res = SettingFacade::update($this->setting, array(
                // 'daily_income_cap' => $validatedData['daily_income_cap'],
                'share_value' => $validatedData['inp_share_value'],
                'usd_to_bte_fee' => $validatedData['usd_to_bte_fee'],
                'bte_to_usd_fee' => $validatedData['bte_to_usd_fee'],
                'withdrawal_fee' => $validatedData['withdrawal_fee'],
                'unhold_period' => $unholdPeriod,
                'unfreeze_period' => $unfreezePeriod,
            ));
        } else {
            $res = SettingFacade::create(array('daily_income_cap' => $validatedData['daily_income_cap']));
        }

        $res = CurrencyPoolFacade::update($this->currencyPool, array('tex_amount' => $validatedData['inp_tex_amount'], 'usdt_amount' => $validatedData['inp_tex_amount']));

        if ($res) {
            Session::flash('alert-success', 'Setting updated successfully');
            return redirect()->route('settings.all');
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('settings.all');
        }
    }
}
