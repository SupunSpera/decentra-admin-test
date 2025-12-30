<?php

namespace domain\Services;

use App\Models\CryptoNetwork;
use Illuminate\Database\Eloquent\Collection;

class CryptoNetworkService
{
    protected $cryptoNetwork;

    public function __construct()
    {
        $this->cryptoNetwork = new CryptoNetwork();
    }

    /**
     * Get CryptoNetwork by id
     *
     * @param  int $id
     * @return CryptoNetwork
     */
    public function get(int $id): CryptoNetwork
    {
        return $this->cryptoNetwork->findOrFail($id);
    }

    /**
     * Get all CryptoNetworks
     *
     * @return Collection
     */
    public function all(): ?Collection
    {
        return $this->cryptoNetwork->orderBy('id', 'desc')->get();
    }

    /**
     * Get all active CryptoNetworks
     *
     * @return Collection
     */
    public function getActive(): ?Collection
    {
        return $this->cryptoNetwork
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Create CryptoNetwork
     *
     * @param  array $data
     * @return CryptoNetwork
     */
    public function create(array $data): CryptoNetwork
    {
        $data = $this->normalizeTokens($data);

        return $this->cryptoNetwork->create($data);
    }

    /**
     * Update CryptoNetwork
     *
     * @param  CryptoNetwork $cryptoNetwork
     * @param  array $data
     * @return bool
     */
    public function update(CryptoNetwork $cryptoNetwork, array $data): bool
    {
        $data = $this->normalizeTokens($data);

        return $cryptoNetwork->update($this->edit($cryptoNetwork, $data));
    }

    /**
     * Delete CryptoNetwork
     *
     * @param  CryptoNetwork $cryptoNetwork
     * @return bool|null
     */
    public function delete(CryptoNetwork $cryptoNetwork): ?bool
    {
        return $cryptoNetwork->delete();
    }

    /**
     * Edit data
     *
     * @param  CryptoNetwork $cryptoNetwork
     * @param  array $data
     * @return array
     */
    protected function edit(CryptoNetwork $cryptoNetwork, array $data): array
    {
        return array_merge($cryptoNetwork->toArray(), $data);
    }

    /**
     * Normalize tokens JSON string/array
     *
     * @param  array $data
     * @return array
     */
    protected function normalizeTokens(array $data): array
    {
        if (isset($data['tokens']) && is_string($data['tokens'])) {
            // Expecting JSON from form textarea
            $decoded = json_decode($data['tokens'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['tokens'] = $decoded;
            }
        }

        return $data;
    }

    /**
     * Get all network tokens grouped by chain_id
     * Returns format: { chain_id: { TOKEN_SYMBOL: contract_address } }
     *
     * @return array
     */
    public function getNetworkTokensMap(): array
    {
        $networks = $this->cryptoNetwork
            ->where('is_active', true)
            ->whereNotNull('tokens')
            ->get();

        $tokensMap = [];

        foreach ($networks as $network) {
            if (!empty($network->tokens)) {
                $tokensMap[$network->chain_id] = $network->tokens;
            }
        }

        return $tokensMap;
    }
}


