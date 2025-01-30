<?php

namespace App\Tests\Entity;

use App\Entity\Events;
use App\Entity\Contacts;
use App\Entity\Locations;
use App\Entity\Tags;
use PHPUnit\Framework\TestCase;
use DateTime;

class EventsTest extends TestCase
{
    private Events $event;

    protected function setUp(): void
    {
        $this->event = new Events();
    }

    public function testInitialState(): void
    {
        // Vérifier l'état initial
        $this->assertNull($this->event->getId());
        $this->assertNull($this->event->getTitle());
        $this->assertEmpty($this->event->getContacts());
        $this->assertEmpty($this->event->getTags());
    }

    public function testTitle(): void
    {
        $title = "Concert de Jazz";
        $this->event->setTitle($title);
        $this->assertEquals($title, $this->event->getTitle());
    }

    public function testDates(): void
    {
        $date = new DateTime();
        
        // Test date de début
        $this->event->setDateStart($date);
        $this->assertEquals($date, $this->event->getDateStart());
        
        // Test date de fin
        $this->event->setDateEnd($date);
        $this->assertEquals($date, $this->event->getDateEnd());
    }

    public function testLocation(): void
    {
        $location = new Locations();
        $location->setAddressName("Salle de concert");
        
        $this->event->setLocation($location);
        $this->assertEquals($location, $this->event->getLocation());
    }

    public function testAddContact(): void
    {
        $contact = new Contacts();
        $contact->setContactPhone("0123456789");
        
        $this->event->addContact($contact);
        $this->assertTrue($this->event->getContacts()->contains($contact));
    }

    public function testRemoveContact(): void
    {
        $contact = new Contacts();
        $this->event->addContact($contact);
        $this->event->removeContact($contact);
        $this->assertFalse($this->event->getContacts()->contains($contact));
    }

    public function testAddTag(): void
    {
        $tag = new Tags();
        $tag->setName("Jazz");
        
        $this->event->addTag($tag);
        $this->assertTrue($this->event->getTags()->contains($tag));
    }
}
