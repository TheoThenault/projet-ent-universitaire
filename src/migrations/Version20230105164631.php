<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230105164631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE formation_ue');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ue AS SELECT id, specialite_id, nom, volume_horaire FROM ue');
        $this->addSql('DROP TABLE ue');
        $this->addSql('CREATE TABLE ue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, specialite_id INTEGER NOT NULL, formation_id INTEGER DEFAULT NULL, nom VARCHAR(255) NOT NULL, volume_horaire INTEGER DEFAULT NULL, CONSTRAINT FK_2E490A9B2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2E490A9B5200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ue (id, specialite_id, nom, volume_horaire) SELECT id, specialite_id, nom, volume_horaire FROM __temp__ue');
        $this->addSql('DROP TABLE __temp__ue');
        $this->addSql('CREATE INDEX IDX_2E490A9B2195E0F0 ON ue (specialite_id)');
        $this->addSql('CREATE INDEX IDX_2E490A9B5200282E ON ue (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formation_ue (formation_id INTEGER NOT NULL, ue_id INTEGER NOT NULL, PRIMARY KEY(formation_id, ue_id), CONSTRAINT FK_C37045E55200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C37045E562E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C37045E562E883B1 ON formation_ue (ue_id)');
        $this->addSql('CREATE INDEX IDX_C37045E55200282E ON formation_ue (formation_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ue AS SELECT id, specialite_id, nom, volume_horaire FROM ue');
        $this->addSql('DROP TABLE ue');
        $this->addSql('CREATE TABLE ue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, specialite_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, volume_horaire INTEGER DEFAULT NULL, CONSTRAINT FK_2E490A9B2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ue (id, specialite_id, nom, volume_horaire) SELECT id, specialite_id, nom, volume_horaire FROM __temp__ue');
        $this->addSql('DROP TABLE __temp__ue');
        $this->addSql('CREATE INDEX IDX_2E490A9B2195E0F0 ON ue (specialite_id)');
    }
}
