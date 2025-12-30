<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MilestoneArchived
{
    use Dispatchable, SerializesModels;

    public $customer;
    public $milestone;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($customer, $milestone)
    {
        $this->customer = $customer;
        $this->milestone = $milestone;
    }
}
