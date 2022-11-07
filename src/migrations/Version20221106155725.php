<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106155725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ue AS SELECT id, nom, volume_horaire FROM ue');
        $this->addSql('DROP TABLE ue');
        $this->addSql('CREATE TABLE ue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, specialite_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, volume_horaire INTEGER DEFAULT NULL, CONSTRAINT FK_2E490A9B2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ue (id, nom, volume_horaire) SELECT id, nom, volume_horaire FROM __temp__ue');
        $this->addSql('DROP TABLE __temp__ue');
        $this->addSql('CREATE INDEX IDX_2E490A9B2195E0F0 ON ue (specialite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__ue AS SELECT id, nom, volume_horaire FROM ue');
        $this->addSql('DROP TABLE ue');
        $this->addSql('CREATE TABLE ue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, volume_horaire INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO ue (id, nom, volume_horaire) SELECT id, nom, volume_horaire FROM __temp__ue');
        $this->addSql('DROP TABLE __temp__ue');
    }
}
