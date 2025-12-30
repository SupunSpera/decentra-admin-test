<?php

namespace App\Http\Controllers;

use domain\Facades\ProjectFacade;

class ProjectController extends Controller
{
    /**
     * Display the projects
     *
     */
    public function all()
    {
        $projects = ProjectFacade::all();

        return view('pages.projects.all', compact('projects'));
    }

    /**
     * new
     *
     * @return void
     */
    public function new()
    {
        return view('pages.projects.new');
    }

    /**
     * edit
     *
     * @return void
     */
    public function edit($id)
    {
        return view('pages.projects.edit', compact('id'));
    }

    /**
     * edit
     *
     * @return void
     */
    public function updates($id)
    {
        return view('pages.projects.updates', compact('id'));
    }


    /**
     * createUpdate
     *
     * @return void
     */
    public function createUpdate($id)
    {
        return view('pages.projects.new-update', compact('id'));
    }

    /**
     * editUpdate
     *
     * @return void
     */
    public function editUpdate($id)
    {
        return view('pages.projects.edit-update', compact('id'));
    }

    /**
     * editUpdate
     *
     * @return void
     */
    public function terms($id)
    {
        return view('pages.projects.terms', compact('id'));
    }
}
