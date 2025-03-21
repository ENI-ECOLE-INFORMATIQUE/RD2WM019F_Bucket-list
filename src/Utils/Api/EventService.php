<?php

namespace App\Utils\Api;

use App\Form\Models\EventSearch;
use App\Models\Event;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EventService
{
    private readonly string $BASE_URL;

    public function __construct(private DenormalizerInterface $denormalizer)
    {
        $this->BASE_URL = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records?limit=20";
    }

    public function search(EventSearch $searchEvent)
    {
        $url = $this->BASE_URL;

        if ($searchEvent->getCityName()) {
            $url .= "&refine.location_city=" . $searchEvent->getCityName();
        }

        if ($searchEvent->getStartDate()) {
            $url .= "&refine.firstdate_begin=" . $searchEvent->getStartDate()->format('Y-m-d');
        }

        $content = file_get_contents($url);
        $events = json_decode($content, true);
        $events = $this->denormalizer->denormalize($events['results'], Event::class . '[]');

        return $events;
    }

}