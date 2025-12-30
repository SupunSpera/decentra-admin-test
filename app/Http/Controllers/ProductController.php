<?php

namespace App\Http\Controllers;

use domain\Facades\ProductFacade;


class ProductController extends Controller
{
     /**
     * Display the products
     *
     */
    public function all()
    {   $products = ProductFacade::all();
        return view('pages.products.all',compact('products'));
    }

     /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.products.new');
    }

      /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        return view('pages.products.edit',compact('id'));
    }
    /**
     * editUpdate
     *
     * @return void
     */
    public function terms($id)
    {
        return view('pages.products.terms', compact('id'));
    }
}
