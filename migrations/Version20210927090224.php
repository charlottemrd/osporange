<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927090224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modalites ADD projet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modalites ADD CONSTRAINT FK_662D296EC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_662D296EC18272 ON modalites (projet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modalites DROP FOREIGN KEY FK_662D296EC18272');
        $this->addSql('DROP INDEX IDX_662D296EC18272 ON modalites');
        $this->addSql('ALTER TABLE modalites DROP projet_id');
    }
}
