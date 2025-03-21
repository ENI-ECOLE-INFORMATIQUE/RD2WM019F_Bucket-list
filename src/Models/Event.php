<?php

namespace App\Models;

class Event
{
    private ?string $thumbnail;
    private ?string $title_fr;
    private ?string $daterange_fr;
    private ?string $location_name;
    private ?string $location_address;
    private ?string $longdescription_fr;
    private ?string $canonicalurl;

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function getTitle_Fr(): ?string
    {
        return $this->title_fr;
    }

    public function setTitleFr(?string $title_fr): void
    {
        $this->title_fr = $title_fr;
    }

    public function getDaterange_Fr(): ?string
    {
        return $this->daterange_fr;
    }

    public function setDaterangeFr(?string $daterange_fr): void
    {
        $this->daterange_fr = $daterange_fr;
    }

    public function getLocation_Name(): ?string
    {
        return $this->location_name;
    }

    public function setLocationName(?string $location_name): void
    {
        $this->location_name = $location_name;
    }

    public function getLocation_Address(): ?string
    {
        return $this->location_address;
    }

    public function setLocationAddress(?string $location_address): void
    {
        $this->location_address = $location_address;
    }

    public function getLongdescription_Fr(): ?string
    {
        return $this->longdescription_fr;
    }

    public function setLongdescriptionFr(?string $longdescription_fr): void
    {
        $this->longdescription_fr = $longdescription_fr;
    }

    public function getCanonicalurl(): ?string
    {
        return $this->canonicalurl;
    }

    public function setCanonicalurl(?string $canonicalurl): void
    {
        $this->canonicalurl = $canonicalurl;
    }




}