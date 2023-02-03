<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203154703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cour (id INT AUTO_INCREMENT NOT NULL, ue_id INT NOT NULL, salle_id INT NOT NULL, enseignant_id INT NOT NULL, groupe_id INT DEFAULT NULL, creneau INT NOT NULL, INDEX IDX_A71F964F62E883B1 (ue_id), INDEX IDX_A71F964FDC304035 (salle_id), INDEX IDX_A71F964FE455FCC0 (enseignant_id), INDEX IDX_A71F964F7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cursus (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, statut_enseignant_id INT DEFAULT NULL, section_id INT DEFAULT NULL, responsable_formation_id INT DEFAULT NULL, INDEX IDX_81A72FA1529CA24F (statut_enseignant_id), INDEX IDX_81A72FA1D823E37A (section_id), UNIQUE INDEX UNIQ_81A72FA176B47FA1 (responsable_formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, INDEX IDX_717E22E35200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant_ue (etudiant_id INT NOT NULL, ue_id INT NOT NULL, INDEX IDX_4C9ADC68DDEAB1A3 (etudiant_id), INDEX IDX_4C9ADC6862E883B1 (ue_id), PRIMARY KEY(etudiant_id, ue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, cursus_id INT NOT NULL, nom VARCHAR(255) NOT NULL, annee INT NOT NULL, INDEX IDX_404021BF40AEF4B9 (cursus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_etudiant (groupe_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_E0DC29937A45358C (groupe_id), INDEX IDX_E0DC2993DDEAB1A3 (etudiant_id), PRIMARY KEY(groupe_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, enseignant_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_FCEC9EFDDEAB1A3 (etudiant_id), UNIQUE INDEX UNIQ_FCEC9EFE455FCC0 (enseignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, batiment VARCHAR(255) NOT NULL, equipement VARCHAR(255) DEFAULT NULL, capacite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, section INT NOT NULL, groupe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut_enseignant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nb_heure_min INT NOT NULL, nb_heure_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue (id INT AUTO_INCREMENT NOT NULL, specialite_id INT NOT NULL, formation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, volume_horaire INT DEFAULT NULL, INDEX IDX_2E490A9B2195E0F0 (specialite_id), INDEX IDX_2E490A9B5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964F62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964FDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id)');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964F7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA1529CA24F FOREIGN KEY (statut_enseignant_id) REFERENCES statut_enseignant (id)');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA1D823E37A FOREIGN KEY (section_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA176B47FA1 FOREIGN KEY (responsable_formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE etudiant_ue ADD CONSTRAINT FK_4C9ADC68DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiant_ue ADD CONSTRAINT FK_4C9ADC6862E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id)');
        $this->addSql('ALTER TABLE groupe_etudiant ADD CONSTRAINT FK_E0DC29937A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_etudiant ADD CONSTRAINT FK_E0DC2993DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id)');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE ue ADD CONSTRAINT FK_2E490A9B5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964F62E883B1');
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964FDC304035');
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964FE455FCC0');
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964F7A45358C');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA1529CA24F');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA1D823E37A');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA176B47FA1');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E35200282E');
        $this->addSql('ALTER TABLE etudiant_ue DROP FOREIGN KEY FK_4C9ADC68DDEAB1A3');
        $this->addSql('ALTER TABLE etudiant_ue DROP FOREIGN KEY FK_4C9ADC6862E883B1');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF40AEF4B9');
        $this->addSql('ALTER TABLE groupe_etudiant DROP FOREIGN KEY FK_E0DC29937A45358C');
        $this->addSql('ALTER TABLE groupe_etudiant DROP FOREIGN KEY FK_E0DC2993DDEAB1A3');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFDDEAB1A3');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFE455FCC0');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B2195E0F0');
        $this->addSql('ALTER TABLE ue DROP FOREIGN KEY FK_2E490A9B5200282E');
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
