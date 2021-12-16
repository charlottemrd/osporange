<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216074749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur ADD interlocuteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA325DC4D72E FOREIGN KEY (interlocuteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_369ECA325DC4D72E ON fournisseur (interlocuteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA325DC4D72E');
        $this->addSql('DROP INDEX IDX_369ECA325DC4D72E ON fournisseur');
        $this->addSql('ALTER TABLE fournisseur DROP interlocuteur_id');
    }
}
