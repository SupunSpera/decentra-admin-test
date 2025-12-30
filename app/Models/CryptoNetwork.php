<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoNetwork extends Model
{
    protected $table = 'crypto_networks';
    
    protected $fillable = [
        'name',
        'chain_id',
        'rpc_http',
        'rpc_ws',
        'tokens',
        'is_active',
    ];

    protected $casts = [
        'tokens' => 'array',           // automatically turns JSON → nice PHP array
        'is_active' => 'boolean',
    ];

    // Super useful helper
    public function getTokenAddress(string $symbol): ?string
    {
        return $this->tokens[$symbol] ?? null;
    }
}