<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130105414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events_contacts (events_id INT NOT NULL, contacts_id INT NOT NULL, INDEX IDX_CE748D709D6A1065 (events_id), INDEX IDX_CE748D70719FB48E (contacts_id), PRIMARY KEY(events_id, contacts_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE events_contacts ADD CONSTRAINT FK_CE748D709D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_contacts ADD CONSTRAINT FK_CE748D70719FB48E FOREIGN KEY (contacts_id) REFERENCES contacts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contacts DROP event_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events_contacts DROP FOREIGN KEY FK_CE748D709D6A1065');
        $this->addSql('ALTER TABLE events_contacts DROP FOREIGN KEY FK_CE748D70719FB48E');
        $this->addSql('DROP TABLE events_contacts');
        $this->addSql('ALTER TABLE contacts ADD event_id INT DEFAULT NULL');
    }
}
