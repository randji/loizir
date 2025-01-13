<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109225820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, address_name LONGTEXT NOT NULL, address_street LONGTEXT NOT NULL, address_zipcode VARCHAR(100) NOT NULL, address_city VARCHAR(100) NOT NULL, lon NUMERIC(10, 8) DEFAULT NULL, lat NUMERIC(11, 8) DEFAULT NULL, transport LONGTEXT DEFAULT NULL, INDEX idx_address_name (address_name(255)), INDEX idx_address_street (address_street(255)), INDEX idx_address_zipcode (address_zipcode), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_6FBC94265E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tags_events (tags_id INT NOT NULL, events_id INT NOT NULL, INDEX IDX_365CEA7A8D7B4FB4 (tags_id), INDEX IDX_365CEA7A9D6A1065 (events_id), PRIMARY KEY(tags_id, events_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tags_events ADD CONSTRAINT FK_365CEA7A8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_events ADD CONSTRAINT FK_365CEA7A9D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_3340157371F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A64D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags_events DROP FOREIGN KEY FK_365CEA7A8D7B4FB4');
        $this->addSql('ALTER TABLE tags_events DROP FOREIGN KEY FK_365CEA7A9D6A1065');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_events');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_3340157371F7E88B');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A64D218E');
    }
}
