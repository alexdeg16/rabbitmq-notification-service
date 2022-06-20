<?php

namespace App\Repository;

use App\Entity\Enum\NotificationStatus;
use App\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notifications>
 *
 * @method Notifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notifications[]    findAll()
 * @method Notifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notifications::class);
    }

    public function add(Notifications $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notifications $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFilteredResultsQuery(
        ?string $subject,
        ?NotificationStatus $notificationStatus,
        ?string $orderBy,
        ?string $orderDirection
    ): Query {
        $qb = $this->createQueryBuilder('notifications');

        if ($subject) {
            $qb->where('notifications.subject = :subject')
                ->setParameter('subject', '%'.$subject.'%');
        }

        if ($notificationStatus) {
            $qb->where('notifications.status = :status')
                ->setParameter('status', $notificationStatus->value);
        }

        if ($orderBy && $orderDirection) {
           switch ($orderBy) {
               case 'receiver':
                   $qb->orderBy('notifications.receiver', $orderDirection);
                   break;
               case 'subject':
                   $qb->orderBy('notifications.subject', $orderDirection);
                   break;
               case 'status':
                   $qb->orderBy('notifications.status', $orderDirection);
                   break;
               case 'sent_date':
                   $qb->orderBy('notifications.sent_date', $orderDirection);
                   break;
           }
        }

        return $qb->getQuery();
    }
}
