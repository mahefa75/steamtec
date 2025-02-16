<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216110228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE machine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE troubleshooting_node (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, machine_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, is_solution TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CA311EA4727ACA70 (parent_id), INDEX IDX_CA311EA4F6B75B26 (machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE troubleshooting_node ADD CONSTRAINT FK_CA311EA4727ACA70 FOREIGN KEY (parent_id) REFERENCES troubleshooting_node (id)');
        $this->addSql('ALTER TABLE troubleshooting_node ADD CONSTRAINT FK_CA311EA4F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE troubleshooting_node DROP FOREIGN KEY FK_CA311EA4727ACA70');
        $this->addSql('ALTER TABLE troubleshooting_node DROP FOREIGN KEY FK_CA311EA4F6B75B26');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE troubleshooting_node');
    }
}
