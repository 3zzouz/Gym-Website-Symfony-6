<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240527140534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nom_activite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_activite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD nom_id INT NOT NULL, ADD coach_id INT NOT NULL, ADD type_id INT NOT NULL, DROP date_fin, DROP coach_name, DROP nom, CHANGE heure_debut heure_debut SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515C8121CE9 FOREIGN KEY (nom_id) REFERENCES nom_activite (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555153C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515C54C8C93 FOREIGN KEY (type_id) REFERENCES type_activite (id)');
        $this->addSql('CREATE INDEX IDX_B8755515C8121CE9 ON activite (nom_id)');
        $this->addSql('CREATE INDEX IDX_B87555153C105691 ON activite (coach_id)');
        $this->addSql('CREATE INDEX IDX_B8755515C54C8C93 ON activite (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555153C105691');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515C8121CE9');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515C54C8C93');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE nom_activite');
        $this->addSql('DROP TABLE type_activite');
        $this->addSql('DROP INDEX IDX_B8755515C8121CE9 ON activite');
        $this->addSql('DROP INDEX IDX_B87555153C105691 ON activite');
        $this->addSql('DROP INDEX IDX_B8755515C54C8C93 ON activite');
        $this->addSql('ALTER TABLE activite ADD date_fin TIME NOT NULL, ADD coach_name VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, DROP nom_id, DROP coach_id, DROP type_id, CHANGE heure_debut heure_debut TIME NOT NULL');
    }
}
