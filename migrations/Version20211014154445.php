<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211014154445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bilanmensuel_profil (bilanmensuel_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_896053B2F23D7AC2 (bilanmensuel_id), INDEX IDX_896053B2275ED078 (profil_id), PRIMARY KEY(bilanmensuel_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bilanmensuel_profil ADD CONSTRAINT FK_896053B2F23D7AC2 FOREIGN KEY (bilanmensuel_id) REFERENCES bilanmensuel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bilanmensuel_profil ADD CONSTRAINT FK_896053B2275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bilanmensuel_profil');
    }
}
