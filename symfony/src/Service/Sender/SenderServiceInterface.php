<?php

namespace App\Service\Sender;

use App\Notifications\AbstractNotification;

interface SenderServiceInterface
{
    public function send(AbstractNotification $notification): bool;
}
