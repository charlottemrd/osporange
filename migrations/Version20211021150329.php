<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021150329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilanmensuel ADD projet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bilanmensuel ADD CONSTRAINT FK_32E3326DC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_32E3326DC18272 ON bilanmensuel (projet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilanmensuel DROP FOREIGN KEY FK_32E3326DC18272');
        $this->addSql('DROP INDEX IDX_32E3326DC18272 ON bilanmensuel');
        $this->addSql('ALTER TABLE bilanmensuel DROP projet_id');
    }
}
