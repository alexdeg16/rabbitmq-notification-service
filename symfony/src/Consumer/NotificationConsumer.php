<?php

namespace App\Consumer;

use App\Entity\Enum\NotificationStatus;
use App\Entity\Notifications;
use App\Notifications\NotificationFactory;
use App\Repository\NotificationsRepository;
use App\Service\Sender\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class NotificationConsumer implements ConsumerInterface
{
    protected NotificationFactory $notificationFactory;

    protected MailerService $mailerService;

    protected EntityManagerInterface $entityManager;

    protected LoggerInterface $logger;

    public function __construct(
        NotificationFactory $notificationFactory,
        MailerService $mailerService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    )
    {
        $this->notificationFactory = $notificationFactory;
        $this->mailerService = $mailerService;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg): bool
    {
        $this->logger->info("Received new message");
        $data = json_decode($msg->body);

        $notification = $this->notificationFactory->createNotification($data->receiver, $data->type, $data->data);

        if (!$notification)
        {
            $this->logger->error(
                sprintf("Notification %s type is unsupported", $data->type)
            );
        }

        $notificationEntity = new Notifications($notification);

        /** @var NotificationsRepository $notificationRepository */
        $notificationRepository = $this->entityManager->getRepository(Notifications::class);
        $notificationRepository->add($notificationEntity, true);

        $this->logger->info(
            sprintf("Notification entity with id %d created", $notificationEntity->getId())
        );


        $this->logger->info("Sending email");

        try {
            $sent = $this->mailerService->send($notification);
            if ($sent) {
                $notificationEntity->setStatus(NotificationStatus::SENT);
                $notificationEntity->setSentTime(new \DateTime());
                $this->logger->info("Email successfully sent");
            } else {
                $this->logger->error("Failed to send email.");
                $notificationEntity->setStatus(NotificationStatus::ERROR);
            }
            $notificationRepository->add($notificationEntity, true);
        } catch (\Exception $exception) {
            $notificationEntity->setStatus(NotificationStatus::ERROR);
            $notificationRepository->add($notificationEntity, true);
            $this->logger->error(
                sprintf("Failed to send email. Error message: %s", $exception->getMessage())
            );
        }

        return true;
    }
}
