<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311212005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create OAUTH2_CONSENT table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE oauth2_user_consent_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE oauth2_user_consent (id INT NOT NULL, client_id VARCHAR(32) DEFAULT NULL, user_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, scopes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C8F05D0119EB6921 ON oauth2_user_consent (client_id)');
        $this->addSql('CREATE INDEX IDX_C8F05D01A76ED395 ON oauth2_user_consent (user_id)');
        $this->addSql('COMMENT ON COLUMN oauth2_user_consent.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN oauth2_user_consent.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN oauth2_user_consent.scopes IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D0119EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_client (identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D01A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE oauth2_user_consent_id_seq CASCADE');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT FK_C8F05D0119EB6921');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT FK_C8F05D01A76ED395');
        $this->addSql('DROP TABLE oauth2_user_consent');
    }
}
