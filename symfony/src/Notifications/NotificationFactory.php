<?php

namespace App\Notifications;

use Psr\Log\LoggerInterface;
use Twig\Environment as TwigEnvironment;

class NotificationFactory
{
    protected LoggerInterface $logger;
    protected TwigEnvironment $twigEnvironment;

    public function __construct(LoggerInterface $logger, TwigEnvironment $twigEnvironment)
    {
        $this->logger = $logger;
        $this->twigEnvironment = $twigEnvironment;
    }

    public function createNotification(string $receiver, string $type, object $data): ?AbstractNotification {
        $className = 'App\\Notifications\\Email\\'.$type.'EmailNotification';

        if(!class_exists($className)) {
            $this->logger->error(
                sprintf(
                    "Notification type %s is not supported",
                    $type
                )
            );
            return null;
        }

        /** @var AbstractNotification $notification */
        $notification = new $className($this->twigEnvironment, $receiver, $data);
        $notification->prepareHtmlContent();

        return $notification;
    }

}
