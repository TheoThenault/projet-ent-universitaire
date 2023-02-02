<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202171335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__enseignant AS SELECT id, statut_enseignant_id, section_id FROM enseignant');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('CREATE TABLE enseignant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, statut_enseignant_id INTEGER DEFAULT NULL, section_id INTEGER DEFAULT NULL, responsable_formation_id INTEGER DEFAULT NULL, CONSTRAINT FK_81A72FA1529CA24F FOREIGN KEY (statut_enseignant_id) REFERENCES statut_enseignant (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81A72FA1D823E37A FOREIGN KEY (section_id) REFERENCES specialite (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81A72FA176B47FA1 FOREIGN KEY (responsable_formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO enseignant (id, statut_enseignant_id, section_id) SELECT id, statut_enseignant_id, section_id FROM __temp__enseignant');
        $this->addSql('DROP TABLE __temp__enseignant');
        $this->addSql('CREATE INDEX IDX_81A72FA1D823E37A ON enseignant (section_id)');
        $this->addSql('CREATE INDEX IDX_81A72FA1529CA24F ON enseignant (statut_enseignant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81A72FA176B47FA1 ON enseignant (responsable_formation_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__salle AS SELECT id, nom, batiment, equipement, capacite FROM salle');
        $this->addSql('DROP TABLE salle');
        $this->addSql('CREATE TABLE salle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, batiment VARCHAR(255) NOT NULL, equipement VARCHAR(255) DEFAULT NULL, capacite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO salle (id, nom, batiment, equipement, capacite) SELECT id, nom, batiment, equipement, capacite FROM __temp__salle');
        $this->addSql('DROP TABLE __temp__salle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__enseignant AS SELECT id, statut_enseignant_id, section_id FROM enseignant');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('CREATE TABLE enseignant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, statut_enseignant_id INTEGER DEFAULT NULL, section_id INTEGER DEFAULT NULL, CONSTRAINT FK_81A72FA1529CA24F FOREIGN KEY (statut_enseignant_id) REFERENCES statut_enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81A72FA1D823E37A FOREIGN KEY (section_id) REFERENCES specialite (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO enseignant (id, statut_enseignant_id, section_id) SELECT id, statut_enseignant_id, section_id FROM __temp__enseignant');
        $this->addSql('DROP TABLE __temp__enseignant');
        $this->addSql('CREATE INDEX IDX_81A72FA1529CA24F ON enseignant (statut_enseignant_id)');
        $this->addSql('CREATE INDEX IDX_81A72FA1D823E37A ON enseignant (section_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__salle AS SELECT id, nom, batiment, equipement, capacite FROM salle');
        $this->addSql('DROP TABLE salle');
        $this->addSql('CREATE TABLE salle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, batiment VARCHAR(50) NOT NULL, equipement VARCHAR(255) DEFAULT NULL, capacite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO salle (id, nom, batiment, equipement, capacite) SELECT id, nom, batiment, equipement, capacite FROM __temp__salle');
        $this->addSql('DROP TABLE __temp__salle');
    }
}
