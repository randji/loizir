<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: EventsRepository::class)]
#[ORM\Index(name: 'idx_title', columns: ['title'])]
#[ORM\Index(name: 'idx_date_start', columns: ['date_start'])]
#[ORM\Index(name: 'idx_price_type', columns: ['price_type'])]
#[ORM\Index(name: 'idx_audience', columns: ['audience'], options: ['lengths' => [255]])]  // Bonne syntaxe pour TEXT
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lead_text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $price_type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $price_detail = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $access_type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $access_link = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $audience = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $childrens = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $groupes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $programs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_event = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cover_url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cover_alt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cover_credit = null;


    #[ORM\ManyToOne(targetEntity: Locations::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Locations $location = null;

    #[ORM\ManyToMany(targetEntity: Tags::class, mappedBy: 'events')]
    private Collection $tags;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $date_description = null;

    #[ORM\ManyToMany(targetEntity: Contacts::class, inversedBy: 'events')]
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return Collection<int, Contacts>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contacts $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
        }
        return $this;
    }

    public function removeContact(Contacts $contact): self
    {
        $this->contacts->removeElement($contact);
        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tags $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getLocation(): ?Locations
    {
        return $this->location;
    }

    public function setLocation(?Locations $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function addLocation(Locations $location): self
{
    if (!$this->location) {
            $this->location = $location;
        }
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLeadText(): ?string
    {
        return $this->lead_text;
    }

    public function setLeadText(?string $lead_text): static
    {
        $this->lead_text = $lead_text;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getPriceType(): ?string
    {
        return $this->price_type;
    }

    public function setPriceType(?string $price_type): static
    {
        $this->price_type = $price_type;

        return $this;
    }

    public function getPriceDetail(): ?string
    {
        return $this->price_detail;
    }

    public function setPriceDetail(?string $price_detail): static
    {
        $this->price_detail = $price_detail;

        return $this;
    }

    public function getAccessType(): ?string
    {
        return $this->access_type;
    }

    public function setAccessType(?string $access_type): static
    {
        $this->access_type = $access_type;

        return $this;
    }

    public function getAccessLink(): ?string
    {
        return $this->access_link;
    }

    public function setAccessLink(?string $access_link): static
    {
        $this->access_link = $access_link;

        return $this;
    }

    public function getAudience(): ?string
    {
        return $this->audience;
    }

    public function setAudience(?string $audience): static
    {
        $this->audience = $audience;

        return $this;
    }

    public function getChildrens(): ?string
    {
        return $this->childrens;
    }

    public function setChildrens(?string $childrens): static
    {
        $this->childrens = $childrens;

        return $this;
    }

    public function getGroupes(): ?string
    {
        return $this->groupes;
    }

    public function setGroupes(?string $groupes): static
    {
        $this->groupes = $groupes;

        return $this;
    }

    public function getPrograms(): ?string
    {
        return $this->programs;
    }

    public function setPrograms(?string $programs): static
    {
        $this->programs = $programs;

        return $this;
    }

    public function getTitleEvent(): ?string
    {
        return $this->title_event;
    }

    public function setTitleEvent(?string $title_event): static
    {
        $this->title_event = $title_event;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->cover_url;
    }

    public function setCoverUrl(?string $cover_url): static
    {
        $this->cover_url = $cover_url;

        return $this;
    }

    public function getCoverAlt(): ?string
    {
        return $this->cover_alt;
    }

    public function setCoverAlt(?string $cover_alt): static
    {
        $this->cover_alt = $cover_alt;

        return $this;
    }

    public function getCoverCredit(): ?string
    {
        return $this->cover_credit;
    }

    public function setCoverCredit(?string $cover_credit): static
    {
        $this->cover_credit = $cover_credit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDescription(): ?string
    {
        return $this->date_description;
    }

    public function setDateDescription(?string $date_description): static
    {
        $this->date_description = $date_description;

        return $this;
    }
}
