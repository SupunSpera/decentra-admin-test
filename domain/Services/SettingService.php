<?php

namespace domain\Services;


use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;


class SettingService
{

    protected $setting;

    public function __construct()
    {
        $this->setting = new Setting();
    }
    /**
     * Get setting using id
     *
     * @param  int $id
     *
     * @return Setting
     */
    public function get(int $id): Setting
    {
        return $this->setting->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->setting->all();
    }
    /**
     * create
     *
     * @param  mixed $setting
     * @return Setting
     */
    public function create(array $setting): Setting
    {
        return $this->setting->create($setting);
    }
    /**
     * Update setting
     *
     * @param Setting $setting
     * @param array $data
     *
     *
     */
    public function update(Setting $setting, array $data)
    {
        return  $setting->update($this->edit($setting, $data));
    }
    /**
     * Edit setting
     *
     * @param Setting $setting
     * @param array $data
     *
     * @return array
     */
    protected function edit(Setting $setting, array $data): array
    {
        return array_merge($setting->toArray(), $data);
    }
    /**
     * Delete a setting
     *
     * @param Setting $setting
     *
     *
     */
    public function delete(Setting $setting)
    {
        return $setting->delete();
    }

    /**
     * getFirstRecord
     *
     * @return void
     */
    public function getFirstRecord(){
        return $this->setting->getFirstRecord();
    }


}
