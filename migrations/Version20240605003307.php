<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605003307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP CONSTRAINT fk_1adad7eb7750b79f');
        $this->addSql('DROP INDEX uniq_1adad7eb7750b79f');
        $this->addSql('ALTER TABLE patient DROP dossier_medical_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE patient ADD dossier_medical_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT fk_1adad7eb7750b79f FOREIGN KEY (dossier_medical_id) REFERENCES dossier_medical (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_1adad7eb7750b79f ON patient (dossier_medical_id)');
    }
}
