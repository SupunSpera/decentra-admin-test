<?php

namespace App\Http\Controllers;

use domain\Facades\GiftFacade;


class GiftController extends Controller
{
     /**
     * Display the gifts
     *
     */
    public function all()
    {   $gifts = GiftFacade::all();
        return view('pages.gifts.all',compact('gifts'));
    }

     /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.gifts.new');
    }

      /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        return view('pages.gifts.edit',compact('id'));
    }
}
