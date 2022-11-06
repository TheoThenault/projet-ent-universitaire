<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106115613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD COLUMN annee INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__formation AS SELECT id, cursus_id, nom FROM formation');
        $this->addSql('DROP TABLE formation');
        $this->addSql('CREATE TABLE formation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cursus_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, CONSTRAINT FK_404021BF40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO formation (id, cursus_id, nom) SELECT id, cursus_id, nom FROM __temp__formation');
        $this->addSql('DROP TABLE __temp__formation');
        $this->addSql('CREATE INDEX IDX_404021BF40AEF4B9 ON formation (cursus_id)');
    }
}
