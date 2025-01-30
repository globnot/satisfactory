<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115162154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE satisfactory_bp_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE satisfactory_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE satisfactory_sbp_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE satisfactory_sbpcfg_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE twitch_chat_ranking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE twitch_chat_viewer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE twitch_chat_vote_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE twitch_chat_vote_session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE twitch_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE satisfactory_bp (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, author VARCHAR(50) DEFAULT NULL, download_count INT DEFAULT NULL, like_count INT DEFAULT NULL, thank_count INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN satisfactory_bp.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN satisfactory_bp.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE satisfactory_image (id INT NOT NULL, satisfactory_bp_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D84ED599CA9CBE48 ON satisfactory_image (satisfactory_bp_id)');
        $this->addSql('COMMENT ON COLUMN satisfactory_image.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN satisfactory_image.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE satisfactory_sbp (id INT NOT NULL, satisfactory_bp_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sbp_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ED0DFFBCA9CBE48 ON satisfactory_sbp (satisfactory_bp_id)');
        $this->addSql('COMMENT ON COLUMN satisfactory_sbp.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN satisfactory_sbp.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE satisfactory_sbpcfg (id INT NOT NULL, satisfactory_bp_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sbpcfg_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6C43038ACA9CBE48 ON satisfactory_sbpcfg (satisfactory_bp_id)');
        $this->addSql('COMMENT ON COLUMN satisfactory_sbpcfg.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN satisfactory_sbpcfg.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE twitch_chat_ranking (id INT NOT NULL, twitch_chat_vote_session_id INT DEFAULT NULL, twitch_chat_viewer_id VARCHAR(255) DEFAULT NULL, points INT DEFAULT NULL, rank INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB2B8CDD18157BE ON twitch_chat_ranking (twitch_chat_vote_session_id)');
        $this->addSql('CREATE INDEX IDX_FB2B8CD98A97F2 ON twitch_chat_ranking (twitch_chat_viewer_id)');
        $this->addSql('CREATE TABLE twitch_chat_viewer (id VARCHAR(255) NOT NULL, total_points INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE twitch_chat_vote (id INT NOT NULL, twitch_chat_viewer_id VARCHAR(255) DEFAULT NULL, twitch_chat_vote_session_id INT DEFAULT NULL, value INT NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5361FCD98A97F2 ON twitch_chat_vote (twitch_chat_viewer_id)');
        $this->addSql('CREATE INDEX IDX_C5361FCDD18157BE ON twitch_chat_vote (twitch_chat_vote_session_id)');
        $this->addSql('COMMENT ON COLUMN twitch_chat_vote.timestamp IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE twitch_chat_vote_session (id INT NOT NULL, state VARCHAR(255) DEFAULT NULL, correct_answer INT DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN twitch_chat_vote_session.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN twitch_chat_vote_session.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE twitch_token (id INT NOT NULL, access_token VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) DEFAULT NULL, expires_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
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
        $this->addSql('ALTER TABLE satisfactory_image ADD CONSTRAINT FK_D84ED599CA9CBE48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE satisfactory_sbp ADD CONSTRAINT FK_ED0DFFBCA9CBE48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE satisfactory_sbpcfg ADD CONSTRAINT FK_6C43038ACA9CBE48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE twitch_chat_ranking ADD CONSTRAINT FK_FB2B8CDD18157BE FOREIGN KEY (twitch_chat_vote_session_id) REFERENCES twitch_chat_vote_session (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE twitch_chat_ranking ADD CONSTRAINT FK_FB2B8CD98A97F2 FOREIGN KEY (twitch_chat_viewer_id) REFERENCES twitch_chat_viewer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE twitch_chat_vote ADD CONSTRAINT FK_C5361FCD98A97F2 FOREIGN KEY (twitch_chat_viewer_id) REFERENCES twitch_chat_viewer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE twitch_chat_vote ADD CONSTRAINT FK_C5361FCDD18157BE FOREIGN KEY (twitch_chat_vote_session_id) REFERENCES twitch_chat_vote_session (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE satisfactory_bp_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE satisfactory_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE satisfactory_sbp_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE satisfactory_sbpcfg_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE twitch_chat_ranking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE twitch_chat_viewer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE twitch_chat_vote_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE twitch_chat_vote_session_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE twitch_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE satisfactory_image DROP CONSTRAINT FK_D84ED599CA9CBE48');
        $this->addSql('ALTER TABLE satisfactory_sbp DROP CONSTRAINT FK_ED0DFFBCA9CBE48');
        $this->addSql('ALTER TABLE satisfactory_sbpcfg DROP CONSTRAINT FK_6C43038ACA9CBE48');
        $this->addSql('ALTER TABLE twitch_chat_ranking DROP CONSTRAINT FK_FB2B8CDD18157BE');
        $this->addSql('ALTER TABLE twitch_chat_ranking DROP CONSTRAINT FK_FB2B8CD98A97F2');
        $this->addSql('ALTER TABLE twitch_chat_vote DROP CONSTRAINT FK_C5361FCD98A97F2');
        $this->addSql('ALTER TABLE twitch_chat_vote DROP CONSTRAINT FK_C5361FCDD18157BE');
        $this->addSql('DROP TABLE satisfactory_bp');
        $this->addSql('DROP TABLE satisfactory_image');
        $this->addSql('DROP TABLE satisfactory_sbp');
        $this->addSql('DROP TABLE satisfactory_sbpcfg');
        $this->addSql('DROP TABLE twitch_chat_ranking');
        $this->addSql('DROP TABLE twitch_chat_viewer');
        $this->addSql('DROP TABLE twitch_chat_vote');
        $this->addSql('DROP TABLE twitch_chat_vote_session');
        $this->addSql('DROP TABLE twitch_token');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
