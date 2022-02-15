<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215102027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des adhérents, adhésions, et table relationnelle dans la BDD';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adhesion (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, prix INT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE souscription_adhesion (id INT AUTO_INCREMENT NOT NULL, adherents_id INT NOT NULL, adhesions_id INT NOT NULL, inscription_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4C0D422C15364D07 (adherents_id), INDEX IDX_4C0D422CFF307B6B (adhesions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE souscription_adhesion ADD CONSTRAINT FK_4C0D422C15364D07 FOREIGN KEY (adherents_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE souscription_adhesion ADD CONSTRAINT FK_4C0D422CFF307B6B FOREIGN KEY (adhesions_id) REFERENCES adhesion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE souscription_adhesion DROP FOREIGN KEY FK_4C0D422C15364D07');
        $this->addSql('ALTER TABLE souscription_adhesion DROP FOREIGN KEY FK_4C0D422CFF307B6B');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE adhesion');
        $this->addSql('DROP TABLE souscription_adhesion');
    }
}
