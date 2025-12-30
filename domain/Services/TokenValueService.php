<?php

namespace domain\Services;

use App\Models\TokenValue;
use Illuminate\Database\Eloquent\Collection;

class TokenValueService
{

    protected $tokenValue;

    public function __construct()
    {
        $this->tokenValue = new TokenValue();
    }
    /**
     * Get tokenValue using id
     *
     * @param  int $id
     *
     * @return TokenValue
     */
    public function get(int $id): TokenValue
    {
        return $this->tokenValue->find($id);
    }

    /**
     * Get all wallets
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->tokenValue->all();
    }
    /**
     * create
     *
     * @param  mixed $tokenValue
     * @return TokenValue
     */
    public function create(array $tokenValue): TokenValue
    {
        return $this->tokenValue->create($tokenValue);
    }
    /**
     * Update tokenValue
     *
     * @param TokenValue $tokenValue
     * @param array $data
     *
     *
     */
    public function update(TokenValue $tokenValue, array $data)
    {
        return  $tokenValue->update($this->edit($tokenValue, $data));
    }
    /**
     * Edit tokenValue
     *
     * @param TokenValue $tokenValue
     * @param array $data
     *
     * @return array
     */
    protected function edit(TokenValue $tokenValue, array $data): array
    {
        return array_merge($tokenValue->toArray(), $data);
    }
    /**
     * Delete a tokenValue
     *
     * @param TokenValue $tokenValue
     *
     *
     */
    public function delete(TokenValue $tokenValue)
    {
        return $tokenValue->delete();
    }

    /**
     * getTokenValueDates
     *
     * @return void
     */
    public function getTokenValueDates()
    {
        return $this->tokenValue->getTokenValueDates();
    }

    /**
     * getMinAndMaxTokenValueOfDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getMinAndMaxTokenValueOfDate($date)
    {
        return $this->tokenValue->getTokenValueDates($date);
    }

    /**
     * getStartingTokenValueOfDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getStartingTokenValueOfDate($date)
    {
        return $this->tokenValue->getTokenValueDates($date);
    }

    /**
     * getClosingTokenValueOfDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getClosingTokenValueOfDate($date)
    {
        return $this->tokenValue->getTokenValueDates($date);
    }



}
