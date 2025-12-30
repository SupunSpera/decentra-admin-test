<?php

namespace domain\Services;


use App\Models\ConnectedProject;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class ConnectedProjectService
{

    protected $connected_project;

    public function __construct()
    {
        $this->connected_project = new ConnectedProject();
    }
    /**
     * Get connected_project using id
     *
     * @param  int $id
     *
     * @return ConnectedProject
     */
    public function get(int $id): ?ConnectedProject
    {
        return $this->connected_project->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->connected_project->all();
    }
    /**
     * create
     *
     * @param  mixed $connected_project
     * @return ConnectedProject
     */
    public function create(array $connected_project): ConnectedProject
    {
        return $this->connected_project->create($connected_project);
    }
    /**
     * Update connected_project
     *
     * @param ConnectedProject $connected_project
     * @param array $data
     *
     *
     */
    public function update(ConnectedProject $connected_project, array $data)
    {
        return  $connected_project->update($this->edit($connected_project, $data));
    }
    /**
     * Edit connected_project
     *
     * @param ConnectedProject $connected_project
     * @param array $data
     *
     * @return array
     */
    protected function edit(ConnectedProject $connected_project, array $data): array
    {
        return array_merge($connected_project->toArray(), $data);
    }
    /**
     * Delete a connected_project
     *
     * @param ConnectedProject $connected_project
     *
     *
     */
    public function delete(ConnectedProject $connected_project)
    {
        return $connected_project->delete();
    }




}
