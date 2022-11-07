<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106145047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialite ADD COLUMN section INTEGER NOT NULL');
        $this->addSql('ALTER TABLE specialite ADD COLUMN groupe VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__specialite AS SELECT id, nom FROM specialite');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('CREATE TABLE specialite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO specialite (id, nom) SELECT id, nom FROM __temp__specialite');
        $this->addSql('DROP TABLE __temp__specialite');
    }
}
