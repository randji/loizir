# Loizir

Loizir est une application de gestion d'événements culturels développée avec Symfony 6 et API Platform.

## Description

Cette application permet de :

- Gérer des événements culturels avec leurs détails (dates, prix, description, etc.)
- Gérer les lieux des événements avec leurs coordonnées géographiques
- Associer des tags aux événements pour une meilleure catégorisation
- Gérer les contacts liés aux événements

## Structure de la Base de Données

L'application utilise 4 entités principales :

### Events

- Informations générales (titre, description, dates)
- Détails pratiques (prix, accès, public cible)
- Images de couverture
- Relations avec les lieux, contacts et tags

### Locations

- Adresse complète
- Coordonnées GPS
- Informations de transport

### Contacts

- Informations de contact (téléphone, email)
- Réseaux sociaux (Facebook, Twitter)
- URL du contact

### Tags

- Système de catégorisation des événements

## Installation

Cloner le repository
git clone [url_du_repo]
Installer les dépendances
composer install
Configurer la base de données dans .env
DATABASE_URL="mysql://[user]:[password]@127.0.0.1:3306/[database_name]"
Créer la base de données
php bin/console doctrine:database:create
Exécuter les migrations
php bin/console doctrine:migrations:migrate
Lancer le serveur de développement
symfony server:start

## Installation avec Docker

```bash
# Cloner le repository
git clone [url_du_repo]

# Lancer les conteneurs Docker
docker-compose up -d

# Installer les dépendances
docker exec -it symfony-app composer install

# Créer la base de données
docker exec -it symfony-app php bin/console doctrine:database:create

# Exécuter les migrations
docker exec -it symfony-app php bin/console doctrine:migrations:migrate
```

L'application sera accessible à l'adresse : http://localhost:8080

### Configuration Docker

Le projet utilise trois conteneurs :

- `symfony-app` : Application PHP/Symfony
- `symfony-db` : Base de données MySQL
- `symfony-nginx` : Serveur web Nginx

## API Endpoints

L'API expose les endpoints suivants :

- `GET /api/events` : Liste des événements
- `GET /api/events/{id}` : Détails d'un événement
- `GET /api/locations` : Liste des lieux
- `GET /api/contacts` : Liste des contacts
- `GET /api/tags` : Liste des tags

## Tests

Des tests unitaires sont disponibles pour les entités principales :

Exécuter les tests
php bin/phpunit

## Import de Données

Une commande personnalisée permet d'importer des données depuis un fichier JSON :

php bin/console app:import-json

## Technologies Utilisées

- Symfony 6
- API Platform
- Doctrine ORM
- MySQL
- PHPUnit

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou à soumettre une pull request.

## Licence

[À définir]
