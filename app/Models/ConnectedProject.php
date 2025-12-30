<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectedProject extends Model
{
    use HasFactory;

    const STATUS = ['DRAFT' => 0, 'PUBLISHED' => 1];

    protected $fillable = [
        'name',
        'public_url',
        'admin_url',
        'status'
    ];
}
