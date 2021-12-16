<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216074527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bilanmensuel (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, idmonthbm_id INT DEFAULT NULL, datemaj DATE DEFAULT NULL, havebeenmodified TINYINT(1) DEFAULT NULL, INDEX IDX_32E3326DC18272 (projet_id), INDEX IDX_32E3326D3BF68AE (idmonthbm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, description VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, INDEX IDX_67F068BCC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cout (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, profil_id INT DEFAULT NULL, nombreprofil DOUBLE PRECISION DEFAULT NULL, INDEX IDX_4E7C337AC18272 (projet_id), INDEX IDX_4E7C337A275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_trois (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, datet DATE DEFAULT NULL, INDEX IDX_7B4169EFC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_lone (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, datereel DATE DEFAULT NULL, INDEX IDX_F54741B0C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_one_plus (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, date DATE DEFAULT NULL, INDEX IDX_2799CA7EC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_two (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, datetwo DATE DEFAULT NULL, INDEX IDX_A0C6CD13C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_zero (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, datezero DATE DEFAULT NULL, INDEX IDX_8B007866C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE datepvinterne (id INT AUTO_INCREMENT NOT NULL, datemy DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adress VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, devise VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE idmonthbm (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT DEFAULT NULL, monthyear DATE DEFAULT NULL, isaccept TINYINT(1) DEFAULT NULL, INDEX IDX_B1F86D05670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE infobilan (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, bilanmensuel_id INT DEFAULT NULL, nombreprofit DOUBLE PRECISION DEFAULT NULL, INDEX IDX_F091EDD1275ED078 (profil_id), INDEX IDX_F091EDD1F23D7AC2 (bilanmensuel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modalites (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, pourcentage DOUBLE PRECISION DEFAULT NULL, datedebut DATE DEFAULT NULL, datefin DATE DEFAULT NULL, conditionsatisfield TINYINT(1) DEFAULT NULL, conditions VARCHAR(255) DEFAULT NULL, isapproved TINYINT(1) DEFAULT NULL, isencours TINYINT(1) DEFAULT NULL, rank INT DEFAULT NULL, decisionsapproved TINYINT(1) DEFAULT NULL, INDEX IDX_662D296EC18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phase (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rang INT NOT NULL, ordere INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE priorite (id INT AUTO_INCREMENT NOT NULL, niveau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, name VARCHAR(255) NOT NULL, tarif DOUBLE PRECISION NOT NULL, INDEX IDX_E6D6B297670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, paiement_id INT DEFAULT NULL, risque_id INT DEFAULT NULL, typebu_id INT DEFAULT NULL, priorite_id INT DEFAULT NULL, phase_id INT DEFAULT NULL, fournisseur_id INT NOT NULL, userchef_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, domaine VARCHAR(255) DEFAULT NULL, sdomaine VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, taux DOUBLE PRECISION DEFAULT NULL, isplanningrespecte TINYINT(1) DEFAULT NULL, highestphase INT DEFAULT NULL, datel1 DATE DEFAULT NULL, date0 DATE DEFAULT NULL, date1 DATE DEFAULT NULL, date2 DATE DEFAULT NULL, date3 DATE DEFAULT NULL, datecrea DATE DEFAULT NULL, datespec DATE DEFAULT NULL, datemaj DATE DEFAULT NULL, datereell1 DATE DEFAULT NULL, datereel0 DATE DEFAULT NULL, datereel1 DATE DEFAULT NULL, datereel2 DATE DEFAULT NULL, datereel3 DATE DEFAULT NULL, garanti INT DEFAULT NULL, debit1bm DOUBLE PRECISION DEFAULT NULL, debit2bm DOUBLE PRECISION DEFAULT NULL, debit3bm DOUBLE PRECISION DEFAULT NULL, isfinish TINYINT(1) DEFAULT NULL, iseligibletobm TINYINT(1) DEFAULT NULL, debit4bm DOUBLE PRECISION DEFAULT NULL, INDEX IDX_50159CA92A4C4478 (paiement_id), INDEX IDX_50159CA94ECC2413 (risque_id), INDEX IDX_50159CA96A1B2AAF (typebu_id), INDEX IDX_50159CA953B4F1DE (priorite_id), INDEX IDX_50159CA999091188 (phase_id), INDEX IDX_50159CA9670C757F (fournisseur_id), INDEX IDX_50159CA9896BB44F (userchef_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pvinternes (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, date_id INT DEFAULT NULL, isvalidate TINYINT(1) DEFAULT NULL, pourcentage DOUBLE PRECISION DEFAULT NULL, ismodified TINYINT(1) DEFAULT NULL, datedebut DATE DEFAULT NULL, datefin DATE DEFAULT NULL, INDEX IDX_11CF5D2AC18272 (projet_id), INDEX IDX_11CF5D2AB897366B (date_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE risque (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rang INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_bu (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, ldap_guid VARCHAR(100) NOT NULL, username LONGTEXT NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', fullusername VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bilanmensuel ADD CONSTRAINT FK_32E3326DC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE bilanmensuel ADD CONSTRAINT FK_32E3326D3BF68AE FOREIGN KEY (idmonthbm_id) REFERENCES idmonthbm (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE cout ADD CONSTRAINT FK_4E7C337AC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE cout ADD CONSTRAINT FK_4E7C337A275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE data_trois ADD CONSTRAINT FK_7B4169EFC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE date_lone ADD CONSTRAINT FK_F54741B0C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE date_one_plus ADD CONSTRAINT FK_2799CA7EC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE date_two ADD CONSTRAINT FK_A0C6CD13C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE date_zero ADD CONSTRAINT FK_8B007866C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE idmonthbm ADD CONSTRAINT FK_B1F86D05670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE infobilan ADD CONSTRAINT FK_F091EDD1275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE infobilan ADD CONSTRAINT FK_F091EDD1F23D7AC2 FOREIGN KEY (bilanmensuel_id) REFERENCES bilanmensuel (id)');
        $this->addSql('ALTER TABLE modalites ADD CONSTRAINT FK_662D296EC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA92A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA94ECC2413 FOREIGN KEY (risque_id) REFERENCES risque (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA96A1B2AAF FOREIGN KEY (typebu_id) REFERENCES type_bu (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA953B4F1DE FOREIGN KEY (priorite_id) REFERENCES priorite (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA999091188 FOREIGN KEY (phase_id) REFERENCES phase (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9896BB44F FOREIGN KEY (userchef_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pvinternes ADD CONSTRAINT FK_11CF5D2AC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE pvinternes ADD CONSTRAINT FK_11CF5D2AB897366B FOREIGN KEY (date_id) REFERENCES datepvinterne (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infobilan DROP FOREIGN KEY FK_F091EDD1F23D7AC2');
        $this->addSql('ALTER TABLE pvinternes DROP FOREIGN KEY FK_11CF5D2AB897366B');
        $this->addSql('ALTER TABLE idmonthbm DROP FOREIGN KEY FK_B1F86D05670C757F');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297670C757F');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9670C757F');
        $this->addSql('ALTER TABLE bilanmensuel DROP FOREIGN KEY FK_32E3326D3BF68AE');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA92A4C4478');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA999091188');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA953B4F1DE');
        $this->addSql('ALTER TABLE cout DROP FOREIGN KEY FK_4E7C337A275ED078');
        $this->addSql('ALTER TABLE infobilan DROP FOREIGN KEY FK_F091EDD1275ED078');
        $this->addSql('ALTER TABLE bilanmensuel DROP FOREIGN KEY FK_32E3326DC18272');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC18272');
        $this->addSql('ALTER TABLE cout DROP FOREIGN KEY FK_4E7C337AC18272');
        $this->addSql('ALTER TABLE data_trois DROP FOREIGN KEY FK_7B4169EFC18272');
        $this->addSql('ALTER TABLE date_lone DROP FOREIGN KEY FK_F54741B0C18272');
        $this->addSql('ALTER TABLE date_one_plus DROP FOREIGN KEY FK_2799CA7EC18272');
        $this->addSql('ALTER TABLE date_two DROP FOREIGN KEY FK_A0C6CD13C18272');
        $this->addSql('ALTER TABLE date_zero DROP FOREIGN KEY FK_8B007866C18272');
        $this->addSql('ALTER TABLE modalites DROP FOREIGN KEY FK_662D296EC18272');
        $this->addSql('ALTER TABLE pvinternes DROP FOREIGN KEY FK_11CF5D2AC18272');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA94ECC2413');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA96A1B2AAF');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9896BB44F');
        $this->addSql('DROP TABLE bilanmensuel');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE cout');
        $this->addSql('DROP TABLE data_trois');
        $this->addSql('DROP TABLE date_lone');
        $this->addSql('DROP TABLE date_one_plus');
        $this->addSql('DROP TABLE date_two');
        $this->addSql('DROP TABLE date_zero');
        $this->addSql('DROP TABLE datepvinterne');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE idmonthbm');
        $this->addSql('DROP TABLE infobilan');
        $this->addSql('DROP TABLE modalites');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE phase');
        $this->addSql('DROP TABLE priorite');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE pvinternes');
        $this->addSql('DROP TABLE risque');
        $this->addSql('DROP TABLE type_bu');
        $this->addSql('DROP TABLE user');
    }
}
