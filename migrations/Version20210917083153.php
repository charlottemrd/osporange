<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917083153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet ADD taux INT DEFAULT NULL, ADD isplanningrespecte TINYINT(1) DEFAULT NULL, ADD highestphase INT DEFAULT NULL, ADD datel1 DATE DEFAULT NULL, ADD date0 DATE DEFAULT NULL, ADD date1 DATE DEFAULT NULL, ADD date2 DATE DEFAULT NULL, ADD date3 DATE DEFAULT NULL, ADD datecrea DATE DEFAULT NULL, ADD datespec DATE DEFAULT NULL, ADD datemaj DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet DROP taux, DROP isplanningrespecte, DROP highestphase, DROP datel1, DROP date0, DROP date1, DROP date2, DROP date3, DROP datecrea, DROP datespec, DROP datemaj');
    }
}
