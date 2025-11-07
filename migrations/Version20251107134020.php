<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107134020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accessory ADD tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accessory ADD CONSTRAINT FK_A1B1251C4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('CREATE INDEX IDX_A1B1251C4D79775F ON accessory (tva_id)');
        $this->addSql('ALTER TABLE trottinette ADD tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trottinette ADD CONSTRAINT FK_445599394D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('CREATE INDEX IDX_445599394D79775F ON trottinette (tva_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accessory DROP FOREIGN KEY FK_A1B1251C4D79775F');
        $this->addSql('ALTER TABLE trottinette DROP FOREIGN KEY FK_445599394D79775F');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP INDEX IDX_A1B1251C4D79775F ON accessory');
        $this->addSql('ALTER TABLE accessory DROP tva_id');
        $this->addSql('DROP INDEX IDX_445599394D79775F ON trottinette');
        $this->addSql('ALTER TABLE trottinette DROP tva_id');
    }
}
