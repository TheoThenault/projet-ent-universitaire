<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202172358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cour (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ue_id INTEGER NOT NULL, salle_id INTEGER NOT NULL, enseignant_id INTEGER NOT NULL, groupe_id INTEGER DEFAULT NULL, creneau INTEGER NOT NULL, CONSTRAINT FK_A71F964F62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A71F964FDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A71F964FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A71F964F7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A71F964F62E883B1 ON cour (ue_id)');
        $this->addSql('CREATE INDEX IDX_A71F964FDC304035 ON cour (salle_id)');
        $this->addSql('CREATE INDEX IDX_A71F964FE455FCC0 ON cour (enseignant_id)');
        $this->addSql('CREATE INDEX IDX_A71F964F7A45358C ON cour (groupe_id)');
        $this->addSql('CREATE TABLE cursus (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE enseignant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, statut_enseignant_id INTEGER DEFAULT NULL, section_id INTEGER DEFAULT NULL, responsable_formation_id INTEGER DEFAULT NULL, CONSTRAINT FK_81A72FA1529CA24F FOREIGN KEY (statut_enseignant_id) REFERENCES statut_enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81A72FA1D823E37A FOREIGN KEY (section_id) REFERENCES specialite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_81A72FA176B47FA1 FOREIGN KEY (responsable_formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_81A72FA1529CA24F ON enseignant (statut_enseignant_id)');
        $this->addSql('CREATE INDEX IDX_81A72FA1D823E37A ON enseignant (section_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81A72FA176B47FA1 ON enseignant (responsable_formation_id)');
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formation_id INTEGER DEFAULT NULL, CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_717E22E35200282E ON etudiant (formation_id)');
        $this->addSql('CREATE TABLE etudiant_ue (etudiant_id INTEGER NOT NULL, ue_id INTEGER NOT NULL, PRIMARY KEY(etudiant_id, ue_id), CONSTRAINT FK_4C9ADC68DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4C9ADC6862E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4C9ADC68DDEAB1A3 ON etudiant_ue (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_4C9ADC6862E883B1 ON etudiant_ue (ue_id)');
        $this->addSql('CREATE TABLE formation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cursus_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, annee INTEGER NOT NULL, CONSTRAINT FK_404021BF40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_404021BF40AEF4B9 ON formation (cursus_id)');
        $this->addSql('CREATE TABLE groupe (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(2) NOT NULL)');
        $this->addSql('CREATE TABLE groupe_etudiant (groupe_id INTEGER NOT NULL, etudiant_id INTEGER NOT NULL, PRIMARY KEY(groupe_id, etudiant_id), CONSTRAINT FK_E0DC29937A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E0DC2993DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E0DC29937A45358C ON groupe_etudiant (groupe_id)');
        $this->addSql('CREATE INDEX IDX_E0DC2993DDEAB1A3 ON groupe_etudiant (etudiant_id)');
        $this->addSql('CREATE TABLE personne (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etudiant_id INTEGER DEFAULT NULL, enseignant_id INTEGER DEFAULT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles CLOB DEFAULT NULL --(DC2Type:array)
        , CONSTRAINT FK_FCEC9EFDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCEC9EFE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFDDEAB1A3 ON personne (etudiant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFE455FCC0 ON personne (enseignant_id)');
        $this->addSql('CREATE TABLE salle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, batiment VARCHAR(255) NOT NULL, equipement VARCHAR(255) DEFAULT NULL, capacite INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE specialite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, section INTEGER NOT NULL, groupe VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE statut_enseignant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nb_heure_min INTEGER NOT NULL, nb_heure_max INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE ue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, specialite_id INTEGER NOT NULL, formation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, volume_horaire INTEGER DEFAULT NULL, CONSTRAINT FK_2E490A9B2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2E490A9B5200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2E490A9B2195E0F0 ON ue (specialite_id)');
        $this->addSql('CREATE INDEX IDX_2E490A9B5200282E ON ue (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cour');
        $this->addSql('DROP TABLE cursus');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE etudiant_ue');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_etudiant');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE statut_enseignant');
        $this->addSql('DROP TABLE ue');
    }
}
