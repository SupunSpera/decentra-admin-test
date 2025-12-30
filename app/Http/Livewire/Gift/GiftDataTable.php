<?php

namespace App\Http\Livewire\Gift;

use App\Models\Gift;
use domain\Facades\GiftFacade;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class GiftDataTable extends LivewireDatatable
{
    public $model = Gift::class;

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
            Column::name('name')->label('Name'),
            Column::name('description')->label('Description'),
            Column::name('token_amount')->label('Token Amount'),
            Column::callback(['status'], function ($status) {
                return $this->status($status);
            })->label('Status'),
            Column::raw("DATE_FORMAT(gifts.created_at, '%Y/%m/%d') AS Created At")->label('Created At'),
            Column::callback(['id', 'status'], function ($id, $status) {
                return view('pages.gifts.actions', ['id' => $id, 'status' => $status]);
            })->label('Actions'),
        ];
    }


    /**
     * status
     *
     * @param  mixed $status
     * @return string
     */
    public function status($status): string
    {
        $data = '<div class="text-center">';
        if ($status == Gift::STATUS['DRAFT']) {
            $data = $data . '<span class="badge badge-warning">DRAFT</span>';
        } else if ($status == Gift::STATUS['PUBLISHED']) {
            $data = $data . '<span class="badge badge-success">Published</span>';
        }
        return $data . '</div>';
    }



    /**
     * publishGift
     *
     * @param  mixed $giftId
     * @return void
     */
    function publishGift(int $giftId)
    {
        $gift = GiftFacade::get($giftId);

        if ($gift->image) {
            $response = GiftFacade::update($gift, array('status' => Gift::STATUS['PUBLISHED']));


            if ($response) {
                Session::flash('alert-success', 'Gift unpublished successfully');
                return redirect()->route('gifts.all')->with($response);
            } else {
                Session::flash('alert-danger', 'Something went wrong!');
                return redirect()->route('gifts.all')->with($response);
            }
        } else {
            Session::flash('alert-warning', 'Update Image Before Publish!');
            return redirect()->route('gifts.all');
        }
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


    /**
     * deleteGift
     *
     * @param  mixed $giftId
     * @return void
     */
    function deleteGift(int $giftId)
    {
        $gift = GiftFacade::get($giftId);
        $response = GiftFacade::delete($gift);


        if ($response) {
            Session::flash('alert-success', 'Gift deleted successfully');
            return redirect()->route('gifts.all')->with($response);
        } else {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->route('gifts.all')->with($response);
        }
    }
}
