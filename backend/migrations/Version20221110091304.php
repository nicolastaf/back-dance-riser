<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110091304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_move CHANGE image image LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE choreography CHANGE image image LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE image image LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE new_request new_request TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE move CHANGE image image LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE school CHANGE new_request new_request TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE style CHANGE image image TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE move CHANGE image image VARCHAR(3000) DEFAULT NULL');
        $this->addSql('ALTER TABLE style CHANGE image image VARCHAR(3000) DEFAULT NULL');
        $this->addSql('ALTER TABLE choreography CHANGE image image VARCHAR(3000) DEFAULT NULL');
        $this->addSql('ALTER TABLE category_move CHANGE image image VARCHAR(3000) DEFAULT NULL');
        $this->addSql('ALTER TABLE school CHANGE new_request new_request TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE image image VARCHAR(3000) DEFAULT NULL');
        $this->addSql('ALTER TABLE member CHANGE new_request new_request TINYINT(1) NOT NULL');
    }
}
