<?php

namespace App\Notifications\Email;

use App\Notifications\AbstractNotification;
use Twig\Environment as TwigEnvironment;

class OrderPlacedEmailNotification extends AbstractNotification
{
    protected string $subject = 'Заказ оформлен!';

    protected string $template_path = 'emails/order_placed.html.twig';

    protected string $notificationType = "OrderPlaced";

    public function __construct(TwigEnvironment $twigEnvironment, string $receiver, object $data)
    {
        parent::__construct($twigEnvironment, $receiver,$data);
    }
}
