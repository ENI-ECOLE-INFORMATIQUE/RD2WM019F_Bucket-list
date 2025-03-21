<?php

namespace App\Controller;

use App\Form\EventSearchType;
use App\Form\Models\EventSearch;
use App\Models\Event;
use App\Utils\Api\EventService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


final class EventController extends AbstractController
{
    #[Route('/events', name: 'event_list')]
    public function list(
        Request      $request,
        EventService $eventService
    ): Response
    {
        $eventSearch = new EventSearch();
        $eventSearchForm = $this->createForm(EventSearchType::class, $eventSearch);

        $eventSearchForm->handleRequest($request);

        $events = $eventService->search($eventSearch);

        return $this->render('event/list.html.twig', [
            'events' => $events,
            'eventSearchForm' => $eventSearchForm
        ]);
    }
}
