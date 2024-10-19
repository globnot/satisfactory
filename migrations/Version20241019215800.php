<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019215800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete satisfactory_comment table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE satisfactory_comment_id_seq CASCADE');
        $this->addSql('ALTER TABLE satisfactory_comment DROP CONSTRAINT fk_f800dd95ca9cbe48');
        $this->addSql('DROP TABLE satisfactory_comment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE satisfactory_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE satisfactory_comment (id INT NOT NULL, satisfactory_bp_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, comment VARCHAR(255) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f800dd95ca9cbe48 ON satisfactory_comment (satisfactory_bp_id)');
        $this->addSql('COMMENT ON COLUMN satisfactory_comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE satisfactory_comment ADD CONSTRAINT fk_f800dd95ca9cbe48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
