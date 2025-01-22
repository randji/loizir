<?php

namespace App\Entity;

use App\Repository\LocationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: LocationsRepository::class)]
#[ORM\Index(name: 'idx_address_name', columns: ['address_name'], options: ['lengths' => [255]])]
#[ORM\Index(name: 'idx_address_street', columns: ['address_street'], options: ['lengths' => [255]])]
#[ORM\Index(name: 'idx_address_zipcode', columns: ['address_zipcode'])]

class Locations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address_name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address_street = null;

    #[ORM\Column(length: 100)]
    private ?string $address_zipcode = null;

    #[ORM\Column(length: 100)]
    private ?string $address_city = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8, nullable: true)]
    private ?string $lon = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 8, nullable: true)]
    private ?string $lat = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $transport = null;
    
    #[ORM\OneToMany(targetEntity: Events::class, mappedBy: 'location')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setLocation($this);
        }
        return $this;
    }

    public function removeEvent(Events $event): self
    {
        if ($this->events->removeElement($event)) {
            if ($event->getLocation() === $this) {
                $event->setLocation(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressName(): ?string
    {
        return $this->address_name;
    }

    public function setAddressName(string $address_name): static
    {
        $this->address_name = $address_name;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(string $address_street): static
    {
        $this->address_street = $address_street;

        return $this;
    }

    public function getAddressZipcode(): ?string
    {
        return $this->address_zipcode;
    }

    public function setAddressZipcode(string $address_zipcode): static
    {
        $this->address_zipcode = $address_zipcode;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    public function setAddressCity(string $address_city): static
    {
        $this->address_city = $address_city;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(?string $lon): static
    {
        $this->lon = $lon;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getTransport(): ?string
    {
        return $this->transport;
    }

    public function setTransport(?string $transport): static
    {
        $this->transport = $transport;

        return $this;
    }
}
