<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604140252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

       
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier_medical ADD groupe_sanguin VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier_medical ADD poids VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier_medical ADD taille VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier_medical ADD tension VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dossier_medical DROP groupe_sanguin');
        $this->addSql('ALTER TABLE dossier_medical DROP poids');
        $this->addSql('ALTER TABLE dossier_medical DROP taille');
        $this->addSql('ALTER TABLE dossier_medical DROP tension');
    }
}
