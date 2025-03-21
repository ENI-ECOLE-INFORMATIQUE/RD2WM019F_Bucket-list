<?php

namespace App\Controller;

use App\Form\EventSearchType;
use App\Form\Models\EventSearch;
use App\Models\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


final class EventController extends AbstractController
{
    #[Route('/events', name: 'event_list')]
    public function list(
        DenormalizerInterface $denormalizer,
        Request               $request
    ): Response
    {
        $url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records?limit=20";

        $eventSearch = new EventSearch();
        $eventSearchForm = $this->createForm(EventSearchType::class, $eventSearch);

        $eventSearchForm->handleRequest($request);

        if ($eventSearchForm->isSubmitted()) {
            if ($eventSearch->getCityName()) {
                $url .= "&refine.location_city=" . $eventSearch->getCityName();
            }
            if ($eventSearch->getStartDate()) {
                $url .= "&refine.firstdate_begin=" . $eventSearch->getStartDate()->format("Y-m-d");
            }
        }

        $events = file_get_contents($url);
        $events = json_decode($events, true);
        $events = $denormalizer->denormalize($events['results'], Event::class . '[]');

        dump($events);

        return $this->render('event/list.html.twig', [
            'events' => $events,
            'eventSearchForm' => $eventSearchForm
        ]);
    }
}
