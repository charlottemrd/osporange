<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215104412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur CHANGE fournisseurldap fournisseurldap LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD userchef_id INT DEFAULT NULL, CHANGE ldapuser ldapuser LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9896BB44F FOREIGN KEY (userchef_id) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_50159CA9896BB44F ON projet (userchef_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur CHANGE fournisseurldap fournisseurldap LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9896BB44F');
        $this->addSql('DROP INDEX IDX_50159CA9896BB44F ON projet');
        $this->addSql('ALTER TABLE projet DROP userchef_id, CHANGE ldapuser ldapuser LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }

    public function isTransactional(): bool
    {
        return false; // TODO: Change the autogenerated stub
    }
}
