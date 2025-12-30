<?php

namespace App\Http\Controllers;

use domain\Facades\MilestoneFacade;


class MilestoneController extends Controller
{
     /**
     * Display the milestones
     *
     */
    public function all()
    {   $milestones = MilestoneFacade::all();
        return view('pages.milestones.all',compact('milestones'));
    }

     /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.milestones.new');
    }

      /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        return view('pages.milestones.edit',compact('id'));
    }
}
