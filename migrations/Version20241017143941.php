<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017143941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE satisfactory_comment ADD satisfactory_bp_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE satisfactory_comment ADD CONSTRAINT FK_F800DD95CA9CBE48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F800DD95CA9CBE48 ON satisfactory_comment (satisfactory_bp_id)');
        $this->addSql('ALTER TABLE satisfactory_image ADD satisfactory_bp_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE satisfactory_image ADD CONSTRAINT FK_D84ED599CA9CBE48 FOREIGN KEY (satisfactory_bp_id) REFERENCES satisfactory_bp (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D84ED599CA9CBE48 ON satisfactory_image (satisfactory_bp_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE satisfactory_image DROP CONSTRAINT FK_D84ED599CA9CBE48');
        $this->addSql('DROP INDEX IDX_D84ED599CA9CBE48');
        $this->addSql('ALTER TABLE satisfactory_image DROP satisfactory_bp_id');
        $this->addSql('ALTER TABLE satisfactory_comment DROP CONSTRAINT FK_F800DD95CA9CBE48');
        $this->addSql('DROP INDEX IDX_F800DD95CA9CBE48');
        $this->addSql('ALTER TABLE satisfactory_comment DROP satisfactory_bp_id');
    }
}
