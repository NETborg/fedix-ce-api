<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\ObjectType;
use Symfony\Component\Validator\Constraints as Assert;

class PlaceModel extends ObjectType
{
    public const TYPE = 'Place';

    protected static string $type = self::TYPE;

    protected float|null $accuracy = null;
    protected float|null $altitude = null;
    protected float|null $latitude = null;
    protected float|null $longitude = null;
    protected float|null $radius = null;
    #[Assert\AtLeastOneOf([
        new Assert\Choice(choices: ['cm', 'feet', 'inches', 'km', 'm', 'miles']),
        new Assert\Url(),
    ])]
    protected string|null $units = null;

    public function getAccuracy(): ?float
    {
        return $this->accuracy;
    }

    public function setAccuracy(?float $accuracy): PlaceModel
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(?float $altitude): PlaceModel
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): PlaceModel
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): PlaceModel
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getRadius(): ?float
    {
        return $this->radius;
    }

    public function setRadius(?float $radius): PlaceModel
    {
        $this->radius = $radius;

        return $this;
    }

    public function getUnits(): ?string
    {
        return $this->units;
    }

    public function setUnits(?string $units): PlaceModel
    {
        $this->units = $units;

        return $this;
    }
}
