<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917085119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet ADD typebu_id INT DEFAULT NULL, ADD priorite_id INT DEFAULT NULL, ADD phase_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA96A1B2AAF FOREIGN KEY (typebu_id) REFERENCES type_bu (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA953B4F1DE FOREIGN KEY (priorite_id) REFERENCES priorite (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA999091188 FOREIGN KEY (phase_id) REFERENCES phase (id)');
        $this->addSql('CREATE INDEX IDX_50159CA96A1B2AAF ON projet (typebu_id)');
        $this->addSql('CREATE INDEX IDX_50159CA953B4F1DE ON projet (priorite_id)');
        $this->addSql('CREATE INDEX IDX_50159CA999091188 ON projet (phase_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA96A1B2AAF');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA953B4F1DE');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA999091188');
        $this->addSql('DROP INDEX IDX_50159CA96A1B2AAF ON projet');
        $this->addSql('DROP INDEX IDX_50159CA953B4F1DE ON projet');
        $this->addSql('DROP INDEX IDX_50159CA999091188 ON projet');
        $this->addSql('ALTER TABLE projet DROP typebu_id, DROP priorite_id, DROP phase_id');
    }
}
