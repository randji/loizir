<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactsRepository::class)]
class Contacts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contact_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_twitter = null;

    #[ORM\ManyToOne(targetEntity: Events::class)]
    #[ORM\JoinColumn(nullable:true)]
    private ?Events $event = null;

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactUrl(): ?string
    {
        return $this->contact_url;
    }

    public function setContactUrl(?string $contact_url): static
    {
        $this->contact_url = $contact_url;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function setContactPhone(?string $contact_phone): static
    {
        $this->contact_phone = $contact_phone;

        return $this;
    }

    public function getContactMail(): ?string
    {
        return $this->contact_mail;
    }

    public function setContactMail(?string $contact_mail): static
    {
        $this->contact_mail = $contact_mail;

        return $this;
    }

    public function getContactFacebook(): ?string
    {
        return $this->contact_facebook;
    }

    public function setContactFacebook(?string $contact_facebook): static
    {
        $this->contact_facebook = $contact_facebook;

        return $this;
    }

    public function getContactTwitter(): ?string
    {
        return $this->contact_twitter;
    }

    public function setContactTwitter(?string $contact_twitter): static
    {
        $this->contact_twitter = $contact_twitter;

        return $this;
    }
}
