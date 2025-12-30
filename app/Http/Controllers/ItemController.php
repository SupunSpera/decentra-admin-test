<?php

namespace App\Http\Controllers;

use domain\Facades\ItemFacade;



class ItemController extends Controller
{
     /**
     * Display the items
     *
     */
    public function all()
    {   $items = ItemFacade::all();
        return view('pages.Items.all',compact('items'));
    }

     /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.Items.new');
    }

      /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        return view('pages.Items.edit',compact('id'));
    }
}
