<?php

namespace App\Entity\Enum;

enum NotificationStatus: string {
    case PENDING = 'pending';
    case SENT = 'sent';
    case ERROR = 'error';
}
