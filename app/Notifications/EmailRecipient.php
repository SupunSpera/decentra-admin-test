<?php

namespace App\Notifications;

use Illuminate\Notifications\Notifiable;

class EmailRecipient
{
    use Notifiable;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
