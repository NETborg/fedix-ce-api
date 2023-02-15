<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214205625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ACTIVATION_LINK table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE activation_link_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE activation_link (id INT NOT NULL, user_id INT DEFAULT NULL, uuid UUID NOT NULL, expires_at VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D2EF46FD17F50A6 ON activation_link (uuid)');
        $this->addSql('CREATE INDEX IDX_1D2EF46FA76ED395 ON activation_link (user_id)');
        $this->addSql('ALTER TABLE activation_link ADD CONSTRAINT FK_1D2EF46FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE activation_link_id_seq CASCADE');
        $this->addSql('ALTER TABLE activation_link DROP CONSTRAINT FK_1D2EF46FA76ED395');
        $this->addSql('DROP TABLE activation_link');
    }
}
