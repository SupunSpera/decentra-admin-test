<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    const STATUS = ['DRAFT' => 0, 'PUBLISHED' => 1];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];
}
