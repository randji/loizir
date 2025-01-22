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
        $filePath = __DIR__ . '/../../public/data/events1.json';

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
            // Debug au début de la boucle
            $io->text('Traitement d\'un nouvel événement');
            $io->text('Contact URL: ' . ($eventData['contact_url'] ?? 'non défini'));
            $io->text('Contact Phone: ' . ($eventData['contact_phone'] ?? 'non défini'));
            $io->text('Contact Mail: ' . ($eventData['contact_mail'] ?? 'non défini'));
            $io->text('Contact Facebook: ' . ($eventData['contact_facebook'] ?? 'non défini'));
            $io->text('Contact Twitter: ' . ($eventData['contact_twitter'] ?? 'non défini'));
            
            
            

            //tableau intermédiare pour Events
            $eventsData =[
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

            $event->setUrl($eventsData['url']);
            $event->setTitle($eventsData['title']);
            $event->setLeadText($eventsData['lead_text']);
            $event->setDateStart(new \DateTime($eventsData['date_start']));
            $event->setDateEnd(new \DateTime($eventsData['date_end']));
            $event->setPriceType($eventsData['price_type']);
            $event->setPriceDetail($eventsData['price_detail']);
            $event->setAccessType($eventsData['access_type']);
            $event->setAccessLink($eventsData['access_link']);
            $event->setAudience($eventsData['audience']);
            $event->setChildrens($eventsData['childrens']);
            $event->setGroupes($eventsData['groupes']);
            $event->setPrograms($eventsData['programs']);
            $event->setTitleEvent($eventsData['title_event']);
            $event->setCoverUrl($eventsData['cover_url']);
            $event->setCoverAlt($eventsData['cover_alt']);
            $event->setCoverCredit($eventsData['cover_credit']);
            $event->setDescription($eventsData['description']);
            $event->setDateDescription($eventsData['date_description']);

            $this->entityManager->persist($event);

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
            $event->addLocation($location);

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

           // Debug - Afficher le JSON complet
           $io->text('Contenu du JSON pour cet événement :');
           $io->text(json_encode($eventData, JSON_PRETTY_PRINT));

           // Créer un nouveau contact
           $contact = new Contacts();
           $contact->setEvent($event);
           $hasContact = false;
           
           // Debug avant traitement
           $io->text('Traitement des contacts pour : ' . $event->getTitle());
           
           // Phone
           if (isset($eventData['contact_phone'])) {
               $contact->setContactPhone($eventData['contact_phone']);
               $hasContact = true;
               $io->text('Phone ajouté : ' . $eventData['contact_phone']);
           }
           
           // Mail
           if (isset($eventData['contact_mail'])) {
               $contact->setContactMail($eventData['contact_mail']);
               $hasContact = true;
               $io->text('Mail ajouté : ' . $eventData['contact_mail']);
           }
           
           // Facebook
           if (isset($eventData['contact_facebook'])) {
               $contact->setContactFacebook($eventData['contact_facebook']);
               $hasContact = true;
               $io->text('Facebook ajouté : ' . $eventData['contact_facebook']);
           }
           
           // Twitter
           if (isset($eventData['contact_twitter'])) {
               $contact->setContactTwitter($eventData['contact_twitter']);
               $hasContact = true;
               $io->text('Twitter ajouté : ' . $eventData['contact_twitter']);
           }

           // URL - on utilise la clé 'url' qui existe
           if (isset($eventData['url'])) {
               $contact->setContactUrl($eventData['url']);
               $hasContact = true;
               $io->text('URL ajoutée : ' . $eventData['url']);
           }

           // Persister uniquement si on a des contacts
           if ($hasContact) {
               $this->entityManager->persist($contact);
               $this->entityManager->flush();
               $io->text('Contact sauvegardé en base');
           }
        }
        
        $io->success('Les données ont été importées avec succès.');
        return Command::SUCCESS;
    }
}
