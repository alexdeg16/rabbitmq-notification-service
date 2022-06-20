<?php

namespace App\Service\Sender;

use App\Notifications\AbstractNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService implements SenderServiceInterface
{
    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(AbstractNotification $notification): bool
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($notification->getReceiver())
            ->subject($notification->getSubject())
            ->html($notification->getContent());

        $this->mailer->send($email);

        return true;
    }
}
