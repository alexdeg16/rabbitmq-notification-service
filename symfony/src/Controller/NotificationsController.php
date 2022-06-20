<?php

namespace App\Controller;

use App\Entity\Enum\NotificationStatus;
use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class NotificationsController extends AbstractController
{
    const ITEMS_PER_PAGE = 10;

    #[Route('/notifications/', name: 'api_notification_list')]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, SerializerInterface $serializer): Response
    {
        $page = $request->query->get('page');
        $subject = $request->query->get('subject');
        $status = $request->query->get('status');
        $orderBy = $request->query->get('orderBy');
        $orderDirection = $request->query->get('orderDirection');

        if ($status) {
            $status = NotificationStatus::tryFrom($status);
        }

        if($orderDirection && !in_array($orderDirection, ['asc', 'desc'])) {
            return new JsonResponse([
                'error_message' => 'Only "asc" and "desc" directions are supported for ordering', 400
            ]);
        }

        /** @var NotificationsRepository $notificationRepository */
        $notificationRepository = $entityManager->getRepository(Notifications::class);
        $query = $notificationRepository->getFilteredResultsQuery($subject, $status, $orderBy, $orderDirection);

        $paginator = $paginator->paginate($query, $page ?? 1, self::ITEMS_PER_PAGE);
        return $this->json(
            [
                'page' => $paginator->getCurrentPageNumber(),
                'total_items' => $paginator->getTotalItemCount(),
                'items_per_page' => $paginator->getItemNumberPerPage(),
                'items' => $serializer->normalize($paginator->getItems(), null, ['groups' => 'notification.list'])
            ]
        );
    }

    #[Route('/notifications/{id}', name: 'api_notification_show')]
    public function show(Notifications $notification, SerializerInterface $serializer): Response
    {
        return $this->json($serializer->normalize($notification, null, ['groups' => 'notification.details']));
    }
}
