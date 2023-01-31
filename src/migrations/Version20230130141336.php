<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130141336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etudiant_ue (etudiant_id INTEGER NOT NULL, ue_id INTEGER NOT NULL, PRIMARY KEY(etudiant_id, ue_id), CONSTRAINT FK_4C9ADC68DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4C9ADC6862E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4C9ADC68DDEAB1A3 ON etudiant_ue (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_4C9ADC6862E883B1 ON etudiant_ue (ue_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE etudiant_ue');
    }
}
