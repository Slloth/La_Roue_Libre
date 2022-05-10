<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502133558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherents (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, telephone VARCHAR(10) NOT NULL, email VARCHAR(255) NULL, cp VARCHAR(7) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_562C7DA3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adhesions (id INT AUTO_INCREMENT NOT NULL, adherents_id INT NOT NULL, prix NUMERIC(5, 2) NOT NULL, subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', branch VARCHAR(255) NOT NULL, INDEX IDX_90757B4715364D07 (adherents_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletters (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, is_verify TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8ECF000CE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_adhesion (id INT AUTO_INCREMENT NOT NULL, type_adhesion VARCHAR(255) NOT NULL, prix NUMERIC(5, 2) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adhesions ADD CONSTRAINT FK_90757B4715364D07 FOREIGN KEY (adherents_id) REFERENCES adherents (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesions DROP FOREIGN KEY FK_90757B4715364D07');
        $this->addSql('DROP TABLE adherents');
        $this->addSql('DROP TABLE adhesions');
        $this->addSql('DROP TABLE newsletters');
        $this->addSql('DROP TABLE type_adhesion');
    }
}
