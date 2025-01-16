<?php

namespace App\Command;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Contacts;
use App\Entity\Events;
use App\Entity\Locations;
use App\Entity\Tags;


#[AsCommand(
    name: 'app:import-json',
    description: 'Import data from JSON file',
)]
class ImportJsonCommand extends Command
{
   
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importe les données des événements depuis un fichier JSON');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Chemin du fichier JSON
        $filePath = __DIR__ . '/../../public/data/events.json';

        if (!file_exists($filePath)) {
            $io->error('Le fichier JSON est introuvable.');
            return Command::FAILURE;
        }

        // Lecture du fichier JSON
        $jsonData = json_decode(file_get_contents($filePath), true);
        if (!$jsonData) {
            $io->error('Le fichier JSON est invalide.');
            return Command::FAILURE;
        }

        foreach ($jsonData as $eventData) {

            //transforme les données pour correspondre à la base de données

            $locationData = [
                'address_name' => $eventData['address_name'] ??'A définir',
                'address_street' =>$eventData['address_street'] ??'A définir',
                'address_zipcode' =>$eventData['address_zipcode'] ??'A définir',
                'address_city' =>$eventData['address_city'] ??'A définir',
                'transport' => $eventData['transport'] ?? 'A définir',
                'lon' => isset($eventData['lat_lon']['lon']) ? $eventData['lat_lon']['lon'] : null,
                'lat' => isset($eventData['lat_lon']['lat']) ? $eventData['lat_lon']['lat'] : null,

            ];

            // Créer ou mettre à jour la locations

            $location = $this->entityManager->getRepository(Locations::class)
            ->findOneBy(['address_name' => $locationData['address_name']]) ?? new Locations();

            $location->setAddressName($locationData['address_name']);
            $location->setAddressStreet($locationData['address_street']);
            $location->setAddressZipcode($locationData['address_zipcode']);
            $location->setAddressCity($locationData['address_city']);
            $location->setLon($locationData['lon']);
            $location->setLat($locationData['lat']);
            $location->setTransport($locationData['transport']);

            $this->entityManager->persist($location);
            

            //tableau intermédiare pour Events
            $eventData =[
                'url' => $eventData['url'] ?? 'A définir',
                'title' => $eventData['title'] ?? 'A définir',
                'lead_text' => $eventData['lead_text'] ?? 'A définir',
                'description' => $eventData['description'] ?? 'A définir',
                'date_description' => $eventData['date_description'] ?? 'A définir',
                'date_start' => $eventData['date_start'] ?? '',
                'date_end' => $eventData['date_end'] ?? '',
                'price_type' => $eventData['price_type'] ?? 'A définir',
                'price_detail' => $eventData['price_detail'] ?? 'A définir',
                'access_type' => $eventData['access_type'] ?? 'A définir',
                'access_link' => $eventData['access_link'] ?? 'A définir',
                'audience' => $eventData['audience'] ?? 'A définir',
                'childrens' => $eventData['childrens'] ?? 'A définir',
                'groupes' => $eventData['groupes'] ?? 'A définir',
                'programs' => $eventData['programs'] ?? 'A définir',
                'title_event' => $eventData['title_event'] ?? 'A définir',
                'cover_url' => $eventData['cover_url'] ?? 'A définir',
                'cover_alt' => $eventData['cover_alt'] ?? 'A définir',
                'cover_credit' => $eventData['cover_credit'] ?? 'A définir',
            ];
            
            //Créer ou mettre à jour l'événement

            $event = $this->entityManager->getRepository(Events::class)
            ->findOneBy(['title' => $eventData['title'] ]) ?? new Events();

            $event->setUrl($eventData['url']);
            $event->setTitle($eventData['title']);
            $event->setLeadText($eventData['lead_text']);
            $event->setDateStart(new \DateTime($eventData['date_start']));
            $event->setDateEnd(new \DateTime($eventData['date_end']));
            $event->setPriceType($eventData['price_type']);
            $event->setPriceDetail($eventData['price_detail']);
            $event->setAccessType($eventData['access_type']);
            $event->setAccessLink($eventData['access_link']);
            $event->setAudience($eventData['audience']);
            $event->setChildrens($eventData['childrens']);
            $event->setGroupes($eventData['groupes']);
            $event->setPrograms($eventData['programs']);
            $event->setTitleEvent($eventData['title_event']);
            $event->setCoverUrl($eventData['cover_url']);
            $event->setCoverAlt($eventData['cover_alt']);
            $event->setCoverCredit($eventData['cover_credit']);
            $event->setDescription($eventData['description']);
            $event->setDateDescription($eventData['date_description']);

            $this->entityManager->persist($event);

            // Gestion des tags
            if (isset($eventData['tags']) && is_array($eventData['tags'])) {
                foreach ($eventData['tags'] as $tagName) {
                    // Rechercher si le tag existe déjà
                    $tag = $this->entityManager->getRepository(Tags::class)
                        ->findOneBy(['name' => $tagName]);
                    
                    // Si le tag n'existe pas, le créer
                    if (!$tag) {
                        $tag = new Tags();
                        $tag->setName($tagName);
                        $this->entityManager->persist($tag);
                    }
                    
                    // Ajouter le tag à l'événement
                    $event->addTag($tag);
                }
            }

            //tableau intermédiaire Contacts
            // $contactData = [
            //     'contact_url' => $eventData['contact_url'] ?? 'A définir',
            //     'contact_phone' => $eventData['contact_phone'] ?? 'A définir',
            //     'contact_mail' => $eventData['contact_mail'] ?? 'A définir',
            //     'contact_facebook' => $eventData['contact_facebook'] ?? 'A définir',
            //     'contact_twitter' => $eventData['contact_twitter'] ?? 'A définir',
            // ];

            // Gestion des contacts
            if (isset($eventData['contact_url']) || isset($eventData['contact_phone']) || 
                isset($eventData['contact_mail']) || isset($eventData['contact_facebook']) || 
                isset($eventData['contact_twitter'])) {
                
                    // Debug - Afficher les données de contact
                var_dump("Données de contact trouvées :", [
                    'url' => $eventData['contact_url'] ?? null,
                    'phone' => $eventData['contact_phone'] ?? null,
                    'mail' => $eventData['contact_mail'] ?? null,
                    'facebook' => $eventData['contact_facebook'] ?? null,
                    'twitter' => $eventData['contact_twitter'] ?? null
                ]);

                $contact = new Contacts(); 
                
                // Vérification et nettoyage des données de contact
                if (isset($eventData['contact_url']) && $eventData['contact_url'] !== 'A définir') {
                    $contact->setContactUrl($eventData['contact_url']);
                }
                
                if (isset($eventData['contact_phone']) && $eventData['contact_phone'] !== 'A définir') {
                    $contact->setContactPhone($eventData['contact_phone']);
                }
                
                if (isset($eventData['contact_mail']) && $eventData['contact_mail'] !== 'A définir') {
                    $contact->setContactMail($eventData['contact_mail']);
                }
                
                if (isset($eventData['contact_facebook']) && $eventData['contact_facebook'] !== 'A définir') {
                    $contact->setContactFacebook($eventData['contact_facebook']);
                }
                
                if (isset($eventData['contact_twitter']) && $eventData['contact_twitter'] !== 'A définir') {
                    $contact->setContactTwitter($eventData['contact_twitter']);
                }

                // Lier l'événement au contact
                $contact->setEvent($event);
                
                 // Debug - Vérifier l'état final du contact
                 var_dump("Contact final:", [
                    'url' => $contact->getContactUrl(),
                    'phone' => $contact->getContactPhone(),
                    'mail' => $contact->getContactMail(),
                    'facebook' => $contact->getContactFacebook(),
                    'twitter' => $contact->getContactTwitter()
                ]);

                
                // Ne persister que si au moins un champ est rempli
                if ($contact->getContactUrl() || $contact->getContactPhone() || 
                    $contact->getContactMail() || $contact->getContactFacebook() || 
                    $contact->getContactTwitter()) {
                    $this->entityManager->persist($contact);
                }
            }

        }
        
        $this->entityManager->flush();

        $io->success('Les données ont été importées avec succès.');
        return Command::SUCCESS;
    }
}
