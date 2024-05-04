<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240504152849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE account_application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE connection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sticker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account_application (id INT NOT NULL, app_uid VARCHAR(255) NOT NULL, account_name VARCHAR(255) NOT NULL, cause VARCHAR(255) NOT NULL, access JSON DEFAULT NULL, subscription JSON NOT NULL, status VARCHAR(255) NOT NULL, vendor_api_token VARCHAR(255) DEFAULT NULL, moysklad_api_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE connection (id INT NOT NULL, account_application_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, credentials JSON DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_29F773666CF4CEC7 ON connection (account_application_id)');
        $this->addSql('CREATE TABLE sticker (id INT NOT NULL, connection_id INT DEFAULT NULL, track_number VARCHAR(255) NOT NULL, moysklad_order_id VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8FEDBCFDDD03F01 ON sticker (connection_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F773666CF4CEC7 FOREIGN KEY (account_application_id) REFERENCES account_application (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sticker ADD CONSTRAINT FK_8FEDBCFDDD03F01 FOREIGN KEY (connection_id) REFERENCES connection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE account_application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE connection_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sticker_id_seq CASCADE');
        $this->addSql('ALTER TABLE connection DROP CONSTRAINT FK_29F773666CF4CEC7');
        $this->addSql('ALTER TABLE sticker DROP CONSTRAINT FK_8FEDBCFDDD03F01');
        $this->addSql('DROP TABLE account_application');
        $this->addSql('DROP TABLE connection');
        $this->addSql('DROP TABLE sticker');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
