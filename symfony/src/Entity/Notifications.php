<?php

namespace App\Entity;

use App\Entity\Enum\NotificationStatus;
use App\Notifications\AbstractNotification;
use App\Repository\NotificationsRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['notification.list', 'notification.details'])]
    private int $id;

    #[ORM\Column(type: 'string')]
    #[Groups(['notification.list', 'notification.details'])]
    private string $receiver;

    #[ORM\Column(type: 'string')]
    #[Groups(['notification.list', 'notification.details'])]
    private string $type;

    #[ORM\Column(type: 'string', enumType: NotificationStatus::class)]
    #[Groups(['notification.list', 'notification.details'])]
    private NotificationStatus $status;

    #[ORM\Column(type: 'string')]
    #[Groups(['notification.list', 'notification.details'])]
    private string $subject;

    #[ORM\Column(type: 'text')]
    #[Groups(['notification.details'])]
    private string $content;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['notification.list', 'notification.details'])]
    private \DateTime $sentTime;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(AbstractNotification $notification)
    {
        $this->type = $notification->getNotificationType();
        $this->receiver = $notification->getReceiver();
        $this->subject = $notification->getSubject();
        $this->content = $notification->getContent();
        $this->status = NotificationStatus::PENDING;
        $this->createdAt = new \DateTime();
    }

    /**
     * @param NotificationStatus $status
     */
    public function setStatus(NotificationStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setSentTime(\DateTime $sentTime): void
    {
        $this->sentTime = $sentTime;
    }

    /**
     * @return string
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return NotificationStatus
     */
    public function getStatus(): NotificationStatus
    {
        return $this->status;
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
     * @return \DateTime
     */
    public function getSentTime(): \DateTime
    {
        return $this->sentTime;
    }



}
