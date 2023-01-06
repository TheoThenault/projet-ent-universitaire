<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104082507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne ADD COLUMN roles CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__personne AS SELECT id, etudiant_id, enseignant_id, email, nom, prenom, password FROM personne');
        $this->addSql('DROP TABLE personne');
        $this->addSql('CREATE TABLE personne (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etudiant_id INTEGER DEFAULT NULL, enseignant_id INTEGER DEFAULT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_FCEC9EFDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCEC9EFE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO personne (id, etudiant_id, enseignant_id, email, nom, prenom, password) SELECT id, etudiant_id, enseignant_id, email, nom, prenom, password FROM __temp__personne');
        $this->addSql('DROP TABLE __temp__personne');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFDDEAB1A3 ON personne (etudiant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFE455FCC0 ON personne (enseignant_id)');
    }
}
