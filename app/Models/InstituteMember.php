<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteMember extends Model
{
    use HasFactory;

    const TYPE = [
        'PRESIDENT' => 0,
        'MEMBER' => 1
    ];

    const STATUS = [
        'PENDING' => 0,
        'ACTIVE' => 1,
        'DISABLED' => 2
    ];

    protected $fillable = [
        'institute_id',
        'customer_id',
        'email',
        'status',
        'type'
    ];



    /**
     * getByEmail
     *
     * @param  mixed $email
     * @return InstituteMember
     */
    public function getByEmail($email): ?InstituteMember
    {
        return $this->where('email', $email)->first();
    }

    /**
     * getByTypeAndStatus
     *
     * @param  mixed $type
     * @param  mixed $status
     * @return void
     */
    public function getByTypeAndStatus($type, $status)
    {
        return $this->where('type', $type)->where('status', $status)->get();
    }


    /**
     * findMemberInPendingStatus
     *
     * @param  mixed $email
     * @return void
     */
    public function findMemberInPendingStatus($email)
    {
            return $this->where('email', $email)
            ->where('type', 1)
            ->where('status', 0)
            ->whereNull('customer_id')
            ->first();
    }
}
