<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106180110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__etudiant AS SELECT id FROM etudiant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, formation_id INTEGER DEFAULT NULL, CONSTRAINT FK_717E22E35200282E FOREIGN KEY (formation_id) REFERENCES formation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO etudiant (id) SELECT id FROM __temp__etudiant');
        $this->addSql('DROP TABLE __temp__etudiant');
        $this->addSql('CREATE INDEX IDX_717E22E35200282E ON etudiant (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__etudiant AS SELECT id FROM etudiant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('CREATE TABLE etudiant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO etudiant (id) SELECT id FROM __temp__etudiant');
        $this->addSql('DROP TABLE __temp__etudiant');
    }
}
