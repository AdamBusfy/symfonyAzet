<?php

namespace App\Entity;

use App\Repository\RecordRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 */
class Record
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date_create", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $date_create;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $temperature;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $pressure;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $humidity;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="records")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    public function __construct()
    {
        $this->date_create = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getDateCreate(): DateTime
    {
        return $this->date_create;
    }

    /**
     * @param DateTime $date_create
     */
    public function setDateCreate(DateTime $date_create): void
    {
        $this->date_create = $date_create;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getPressure(): ?string
    {
        return $this->pressure;
    }

    public function setPressure(string $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?string
    {
        return $this->humidity;
    }

    public function setHumidity(string $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
