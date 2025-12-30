<?php

namespace App\Http\Livewire\Gift;

use App\Models\CustomerGift;
use App\Models\Gift;
use domain\Facades\CustomerFacade;
use domain\Facades\GiftFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class GiftPurchaseDataTable extends LivewireDatatable
{
    public $model = CustomerGift::class;

    protected $listeners = ['deleteGift', 'unpublishGift', 'publishGift'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function columns()
    {
        return [
            NumberColumn::name('id')->label('ID')->sortBy('id'),
            Column::callback(['gift_id'], function ($gift_id) {
                return $this->getGiftDetails($gift_id);
            })->label('Gift'),
             Column::callback(['customer_id'], function ($customer_id) {
                return $this->getCustomerDetails($customer_id);
            })->label('Customer'),
            Column::raw("DATE_FORMAT(customer_gifts.created_at, '%Y/%m/%d') AS Created At")->label('Sent At'),

        ];
    }


    /**
     * getGiftDetail
     *
     * @param  mixed $getGiftDetails
     * @return mixed
     */
    public function getGiftDetails($giftId): mixed
    {
        $gift= GiftFacade::get($giftId);
        if($gift){

        }
        $data = '<div class="text-center">'.$gift->name.'</div>';

        return $data ;
    }

     /**
     * getCustomerDetails
     *
     * @param  mixed $getCustomerDetails
     * @return string
     */
    public function getCustomerDetails($customerId): string
    {
        $customer= CustomerFacade::get($customerId);
        if($customer){

        }
        $data = '<div class="text-center">'.$customer->email.'</div>';

        return $data ;
    }


    /**
     * unpublishGift
     *
     * @param  mixed $giftId
     * @return void
     */
    function unpublishGift(int $giftId)
    {
        $gift = GiftFacade::get($giftId);
        $response = GiftFacade::update($gift, array('status' => Gift::STATUS['DRAFT']));


        if ($response) {
            Session::flash('alert-success', 'Gift unpublished successfully');
            return redirect()->route('gifts.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('gifts.all')->with($response);
        }
    }
}
