<?php

namespace App\Controller;

use App\Models\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


final class EventController extends AbstractController
{
    #[Route('/events', name: 'event_list')]
    public function list(
        DenormalizerInterface $denormalizer
    ): Response
    {
        $events = file_get_contents("https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records?limit=20");
        $events = json_decode($events, true);
        $events = $denormalizer->denormalize($events['results'], Event::class . '[]');

        dump($events);

        return $this->render('event/list.html.twig', [
            'events' => $events
        ]);
    }
}
