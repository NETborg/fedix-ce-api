<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207170058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created ACTOR table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE actor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE actor (id INT NOT NULL, uuid UUID NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, name_map JSON DEFAULT NULL, summary TEXT DEFAULT NULL, summary_map JSON DEFAULT NULL, preferred_username VARCHAR(255) NOT NULL, public_key TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN actor.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN actor.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN actor.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_447556F9772E836A ON actor (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_447556F9EE983A18 ON actor (preferred_username)');
        $this->addSql('CREATE TABLE user_actor (user_id INT NOT NULL, actor_id INT NOT NULL, PRIMARY KEY(user_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_A6B7ADA1A76ED395 ON user_actor (user_id)');
        $this->addSql('CREATE INDEX IDX_A6B7ADA110DAF24A ON user_actor (actor_id)');
        $this->addSql('ALTER TABLE user_actor ADD CONSTRAINT FK_A6B7ADA1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_actor ADD CONSTRAINT FK_A6B7ADA110DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE actor ALTER uuid TYPE UUID');
        $this->addSql('COMMENT ON COLUMN actor.uuid IS NULL');
        $this->addSql('ALTER INDEX uniq_447556f9772e836a RENAME TO UNIQ_447556F9D17F50A6');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE actor ALTER uuid TYPE UUID');
        $this->addSql('COMMENT ON COLUMN actor.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER INDEX uniq_447556f9d17f50a6 RENAME TO uniq_447556f9772e836a');
        $this->addSql('DROP SEQUENCE actor_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_actor DROP CONSTRAINT FK_A6B7ADA1A76ED395');
        $this->addSql('ALTER TABLE user_actor DROP CONSTRAINT FK_A6B7ADA110DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE user_actor');
    }
}
