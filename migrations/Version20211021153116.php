<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021153116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilanmensuel DROP FOREIGN KEY FK_32E3326D670C757F');
        $this->addSql('DROP INDEX IDX_32E3326D670C757F ON bilanmensuel');
        $this->addSql('ALTER TABLE bilanmensuel CHANGE fournisseur_id idmonthbm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bilanmensuel ADD CONSTRAINT FK_32E3326D3BF68AE FOREIGN KEY (idmonthbm_id) REFERENCES idmonthbm (id)');
        $this->addSql('CREATE INDEX IDX_32E3326D3BF68AE ON bilanmensuel (idmonthbm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bilanmensuel DROP FOREIGN KEY FK_32E3326D3BF68AE');
        $this->addSql('DROP INDEX IDX_32E3326D3BF68AE ON bilanmensuel');
        $this->addSql('ALTER TABLE bilanmensuel CHANGE idmonthbm_id fournisseur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bilanmensuel ADD CONSTRAINT FK_32E3326D670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('CREATE INDEX IDX_32E3326D670C757F ON bilanmensuel (fournisseur_id)');
    }

    public function isTransactional(): bool
    {
        return false; // TODO: Change the autogenerated stub
    }
}
