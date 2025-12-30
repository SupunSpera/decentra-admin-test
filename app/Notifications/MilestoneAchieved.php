<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MilestoneAchieved extends Notification
{
    use Queueable;
    protected $customer;
    protected $milestone;

    public function __construct($customer, $milestone)
    {
        $this->customer = $customer;
        $this->milestone = $milestone;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Milestone Archived")
            ->markdown('mail.milestone-archived', [
                'milestone' => $this->milestone,
                'customer' => $this->customer
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
