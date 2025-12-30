<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteWithdrawalApproval extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING' => 0,
        'APPROVED' => 1
    ];

    protected $fillable = [
        'institute_id',
        'withdrawal_id',
        'member_id',
        'status'
    ];
}
