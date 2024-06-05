<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605033557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP CONSTRAINT fk_1adad7eb91ef7eaa');
        $this->addSql('DROP INDEX uniq_1adad7eb91ef7eaa');
        $this->addSql('ALTER TABLE patient DROP rendez_vous_id');
        $this->addSql('ALTER TABLE rendez_vous ADD patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65E8AA0A6B899279 ON rendez_vous (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE patient ADD rendez_vous_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT fk_1adad7eb91ef7eaa FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_1adad7eb91ef7eaa ON patient (rendez_vous_id)');
        $this->addSql('ALTER TABLE rendez_vous DROP CONSTRAINT FK_65E8AA0A6B899279');
        $this->addSql('DROP INDEX IDX_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP patient_id');
    }
}
