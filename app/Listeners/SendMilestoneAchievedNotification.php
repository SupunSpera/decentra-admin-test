<?php
namespace App\Listeners;

use App\Events\MilestoneArchived;
use App\Notifications\EmailRecipient;
use App\Notifications\MilestoneAchieved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendMilestoneAchievedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  MilestoneArchived  $event
     * @return void
     */
    public function handle(MilestoneArchived $event)
    {
        // $event->customer->notify(new MilestoneAchieved($event->customer, $event->milestone));
             $emailRecipient = new EmailRecipient($event->customer->email);
         Notification::send($emailRecipient, new MilestoneAchieved($event->customer,$event->milestone));
    }
}
