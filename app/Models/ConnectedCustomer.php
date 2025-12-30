<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectedCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'project_id'

    ];

     /**
     * getByCustomerId
     *
     * @param  mixed $email
     * @return void
     */
    public function getByCustomerId($customerId)
    {
        return $this->where('customer_id', $customerId)->first();
    }
}
