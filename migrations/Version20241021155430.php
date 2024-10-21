<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021155430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove download_url_sbp and download_url_sbpcfg from satisfactory_bp';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE satisfactory_bp DROP download_url_sbp');
        $this->addSql('ALTER TABLE satisfactory_bp DROP download_url_sbpcfg');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE satisfactory_bp ADD download_url_sbp VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE satisfactory_bp ADD download_url_sbpcfg VARCHAR(255) DEFAULT NULL');
    }
}
