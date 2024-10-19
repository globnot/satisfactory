<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019220955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add like_count and thank_count to satisfactory_bp';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE satisfactory_bp ADD like_count INT DEFAULT NULL');
        $this->addSql('ALTER TABLE satisfactory_bp ADD thank_count INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE satisfactory_bp DROP like_count');
        $this->addSql('ALTER TABLE satisfactory_bp DROP thank_count');
    }
}
