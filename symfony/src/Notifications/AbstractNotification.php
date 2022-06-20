<?php

namespace App\Notifications;

use Twig\Environment as TwigEnvironment;

abstract class AbstractNotification
{
    protected TwigEnvironment $twigEnvironment;

    protected string $receiver;

    protected ?object $data;

    protected string $template_path;

    protected string $subject;

    protected string $content;

    protected string $notificationType;

    public function __construct(TwigEnvironment $twigEnvironment, string $receiver, ?object $data = null)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->receiver = $receiver;
        $this->data = $data;
    }

    public function prepareHtmlContent(): void{
        $this->content = $this->twigEnvironment->render($this->template_path, [
            'data' => $this->data
        ]);
    }
    /**
     * @return string
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }

    /**
     * @return object|null
     */
    public function getData(): ?object
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->template_path;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getNotificationType(): string
    {
        return $this->notificationType;
    }


}
