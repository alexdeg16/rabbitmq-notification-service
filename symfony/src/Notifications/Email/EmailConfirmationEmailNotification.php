<?php

namespace App\Notifications\Email;

use App\Notifications\AbstractNotification;
use Twig\Environment as TwigEnvironment;

class EmailConfirmationEmailNotification extends AbstractNotification
{
    protected string $subject = 'Регистрация почти завершена!';

    protected string $template_path = 'emails/user_confirm_email.html.twig';

    protected string $notificationType = "EmailConfirmation";

    public function __construct(TwigEnvironment $twigEnvironment, string $receiver, object $data)
    {
        parent::__construct($twigEnvironment, $receiver, $data);
    }
}
