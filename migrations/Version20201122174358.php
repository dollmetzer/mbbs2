<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201122174358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role ADD created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role DROP created_on, DROP updated_on');
        $this->addSql('ALTER TABLE user DROP created_on, DROP updated_on');
    }
}
