<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241020111339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add satisfactory_sbpcfg table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE satisfactory_sbpcfg_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE satisfactory_sbpcfg (id INT NOT NULL, satisfactory_bp_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sbpcfg_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6C43038ACA9CBE48 ON satisfactory_sbpcfg (satisfactory_bp_id)');
        $this->addSql('COMMENT ON COLUMN satisfactory_sbpcfg.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN satisfactory_sbpcfg.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE satisfactory_sbpcfg ADD CONSTRAINT FK_6C43038ACA9CBE48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE satisfactory_sbpcfg_id_seq CASCADE');
        $this->addSql('ALTER TABLE satisfactory_sbpcfg DROP CONSTRAINT FK_6C43038ACA9CBE48');
        $this->addSql('DROP TABLE satisfactory_sbpcfg');
    }
}
