<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530132958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE dossier_medical_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE patient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rendez_vous_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE dossier_medical (id INT NOT NULL, numero_dossier VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, dossier_medical_id INT DEFAULT NULL, rendez_vous_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone INT DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EB7750B79F ON patient (dossier_medical_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ADAD7EB91EF7EAA ON patient (rendez_vous_id)');
        $this->addSql('CREATE TABLE rendez_vous (id INT NOT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB7750B79F FOREIGN KEY (dossier_medical_id) REFERENCES dossier_medical (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB91EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE dossier_medical_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE patient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rendez_vous_id_seq CASCADE');
        $this->addSql('ALTER TABLE patient DROP CONSTRAINT FK_1ADAD7EB7750B79F');
        $this->addSql('ALTER TABLE patient DROP CONSTRAINT FK_1ADAD7EB91EF7EAA');
        $this->addSql('DROP TABLE dossier_medical');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE rendez_vous');
    }
}
