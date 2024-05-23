<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522192438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(3) NOT NULL, label VARCHAR(100) NOT NULL, region VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mairie (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, code_insee VARCHAR(6) NOT NULL, code_postal VARCHAR(5) NOT NULL, label VARCHAR(180) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, site_web VARCHAR(255) DEFAULT NULL, telephone VARCHAR(25) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, latitude VARCHAR(20) DEFAULT NULL, longitude VARCHAR(20) DEFAULT NULL, date_maj DATE NOT NULL, INDEX IDX_3946A254CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mairie ADD CONSTRAINT FK_3946A254CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mairie DROP FOREIGN KEY FK_3946A254CCF9E01E');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE mairie');
    }
}
