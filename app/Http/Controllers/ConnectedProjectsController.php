<?php

namespace App\Http\Controllers;

class ConnectedProjectsController extends Controller
{
    /**
     * all
     *
     * @return void
     */
    public function all()
    {
        return view('pages.connected_projects.all');

    }

      /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.connected_projects.new');
    }

     /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        return view('pages.connected_projects.edit', compact('id'));
    }
}
