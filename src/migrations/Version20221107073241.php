<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107073241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cour (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ue_id INTEGER NOT NULL, salle_id INTEGER NOT NULL, enseignant_id INTEGER NOT NULL, creneau INTEGER NOT NULL, CONSTRAINT FK_A71F964F62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A71F964FDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A71F964FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A71F964F62E883B1 ON cour (ue_id)');
        $this->addSql('CREATE INDEX IDX_A71F964FDC304035 ON cour (salle_id)');
        $this->addSql('CREATE INDEX IDX_A71F964FE455FCC0 ON cour (enseignant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cour');
    }
}
