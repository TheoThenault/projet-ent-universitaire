<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103164144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__salle AS SELECT id, nom, batiment, equipement, capacité FROM salle');
        $this->addSql('DROP TABLE salle');
        $this->addSql('CREATE TABLE salle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, batiment VARCHAR(50) NOT NULL, equipement VARCHAR(255) DEFAULT NULL, capacite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO salle (id, nom, batiment, equipement, capacite) SELECT id, nom, batiment, equipement, capacité FROM __temp__salle');
        $this->addSql('DROP TABLE __temp__salle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__salle AS SELECT id, nom, batiment, equipement, capacite FROM salle');
        $this->addSql('DROP TABLE salle');
        $this->addSql('CREATE TABLE salle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, batiment VARCHAR(50) NOT NULL, equipement VARCHAR(255) DEFAULT NULL, capacité INTEGER NOT NULL)');
        $this->addSql('INSERT INTO salle (id, nom, batiment, equipement, capacité) SELECT id, nom, batiment, equipement, capacite FROM __temp__salle');
        $this->addSql('DROP TABLE __temp__salle');
    }
}
