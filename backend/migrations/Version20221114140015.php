<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114140015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE move ADD school_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E3778C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('CREATE INDEX IDX_EF3E3778C32A47EE ON move (school_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE move DROP FOREIGN KEY FK_EF3E3778C32A47EE');
        $this->addSql('DROP INDEX IDX_EF3E3778C32A47EE ON move');
        $this->addSql('ALTER TABLE move DROP school_id');
    }
}
