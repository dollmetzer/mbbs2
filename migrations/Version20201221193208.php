<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201221193208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, identifier VARCHAR(64) NOT NULL, name VARCHAR(32) NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, marking VARCHAR(255) DEFAULT NULL, created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1F1B251E772E836A (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, workflow_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A393D2FB2C7C2CBA (workflow_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stuff (id INT AUTO_INCREMENT NOT NULL, workflow_id INT DEFAULT NULL, state_id INT DEFAULT NULL, identifier VARCHAR(64) NOT NULL, name VARCHAR(32) NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5941F83E772E836A (identifier), INDEX IDX_5941F83E2C7C2CBA (workflow_id), INDEX IDX_5941F83E5D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transition (id INT AUTO_INCREMENT NOT NULL, fromstate_id INT DEFAULT NULL, tostate_id INT DEFAULT NULL, workflow_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F715A75AF7CE3E13 (fromstate_id), INDEX IDX_F715A75AFF08E049 (tostate_id), INDEX IDX_F715A75A2C7C2CBA (workflow_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transition_2_role (transition_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_FD204CA98BF1A064 (transition_id), INDEX IDX_FD204CA9D60322AC (role_id), PRIMARY KEY(transition_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workflow (id INT AUTO_INCREMENT NOT NULL, initialstate_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, type VARCHAR(16) DEFAULT \'state_machine\' NOT NULL, audit_trail TINYINT(1) DEFAULT \'1\' NOT NULL, created_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_65C59816C5E2B714 (initialstate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE state ADD CONSTRAINT FK_A393D2FB2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id)');
        $this->addSql('ALTER TABLE stuff ADD CONSTRAINT FK_5941F83E2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id)');
        $this->addSql('ALTER TABLE stuff ADD CONSTRAINT FK_5941F83E5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE transition ADD CONSTRAINT FK_F715A75AF7CE3E13 FOREIGN KEY (fromstate_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE transition ADD CONSTRAINT FK_F715A75AFF08E049 FOREIGN KEY (tostate_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE transition ADD CONSTRAINT FK_F715A75A2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES workflow (id)');
        $this->addSql('ALTER TABLE transition_2_role ADD CONSTRAINT FK_FD204CA98BF1A064 FOREIGN KEY (transition_id) REFERENCES transition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transition_2_role ADD CONSTRAINT FK_FD204CA9D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workflow ADD CONSTRAINT FK_65C59816C5E2B714 FOREIGN KEY (initialstate_id) REFERENCES state (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stuff DROP FOREIGN KEY FK_5941F83E5D83CC1');
        $this->addSql('ALTER TABLE transition DROP FOREIGN KEY FK_F715A75AF7CE3E13');
        $this->addSql('ALTER TABLE transition DROP FOREIGN KEY FK_F715A75AFF08E049');
        $this->addSql('ALTER TABLE workflow DROP FOREIGN KEY FK_65C59816C5E2B714');
        $this->addSql('ALTER TABLE transition_2_role DROP FOREIGN KEY FK_FD204CA98BF1A064');
        $this->addSql('ALTER TABLE state DROP FOREIGN KEY FK_A393D2FB2C7C2CBA');
        $this->addSql('ALTER TABLE stuff DROP FOREIGN KEY FK_5941F83E2C7C2CBA');
        $this->addSql('ALTER TABLE transition DROP FOREIGN KEY FK_F715A75A2C7C2CBA');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE stuff');
        $this->addSql('DROP TABLE transition');
        $this->addSql('DROP TABLE transition_2_role');
        $this->addSql('DROP TABLE workflow');
    }
}
