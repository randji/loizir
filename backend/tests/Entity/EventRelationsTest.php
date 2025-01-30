<?php

namespace App\Tests\Entity;

use App\Entity\Events;
use App\Entity\Contacts;
use App\Entity\Locations;
use App\Entity\Tags;
use PHPUnit\Framework\TestCase;

class EventRelationsTest extends TestCase
{
    private Events $event;

    protected function setUp(): void
    {
        $this->event = new Events();
    }

    public function testEventLocationRelation(): void
    {
        // Créer une location
        $location = new Locations();
        $location->setAddressName('Salle de Concert');
        $location->setAddressStreet('123 rue de la Musique');
        
        // Lier l'événement à la location
        $this->event->setLocation($location);
        
        // Vérifier la relation
        $this->assertSame($location, $this->event->getLocation());
        $this->assertEquals('Salle de Concert', $this->event->getLocation()->getAddressName());
    }

    public function testEventContactsRelation(): void
    {
        // Créer un contact
        $contact = new Contacts();
        $contact->setContactPhone('0123456789');
        $contact->setContactMail('test@test.com');
        
        // Ajouter le contact à l'événement
        $this->event->addContact($contact);
        
        // Vérifier la relation
        $this->assertTrue($this->event->getContacts()->contains($contact));
        $this->assertCount(1, $this->event->getContacts());
        
        // Tester la suppression
        $this->event->removeContact($contact);
        $this->assertCount(0, $this->event->getContacts());
    }

    public function testEventTagsRelation(): void
    {
        // Créer des tags
        $tag1 = new Tags();
        $tag1->setName('Concert');
        
        $tag2 = new Tags();
        $tag2->setName('Jazz');
        
        // Ajouter les tags à l'événement
        $this->event->addTag($tag1);
        $this->event->addTag($tag2);
        
        // Vérifier les relations
        $this->assertCount(2, $this->event->getTags());
        $this->assertTrue($this->event->getTags()->contains($tag1));
        $this->assertTrue($this->event->getTags()->contains($tag2));
    }
} 