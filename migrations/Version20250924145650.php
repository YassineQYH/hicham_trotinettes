<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250924145650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accessory DROP FOREIGN KEY FK_A1B1251CC93EA6F2');
        $this->addSql('ALTER TABLE trotinette DROP FOREIGN KEY FK_F3E399CEC93EA6F2');
        $this->addSql('DROP TABLE model_trotinette');
        $this->addSql('DROP INDEX IDX_A1B1251CC93EA6F2 ON accessory');
        $this->addSql('ALTER TABLE accessory DROP model_trotinette_id');
        $this->addSql('DROP INDEX IDX_F3E399CEC93EA6F2 ON trotinette');
        $this->addSql('ALTER TABLE trotinette DROP model_trotinette_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE model_trotinette (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_best TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE accessory ADD model_trotinette_id INT NOT NULL');
        $this->addSql('ALTER TABLE accessory ADD CONSTRAINT FK_A1B1251CC93EA6F2 FOREIGN KEY (model_trotinette_id) REFERENCES model_trotinette (id)');
        $this->addSql('CREATE INDEX IDX_A1B1251CC93EA6F2 ON accessory (model_trotinette_id)');
        $this->addSql('ALTER TABLE trotinette ADD model_trotinette_id INT NOT NULL');
        $this->addSql('ALTER TABLE trotinette ADD CONSTRAINT FK_F3E399CEC93EA6F2 FOREIGN KEY (model_trotinette_id) REFERENCES model_trotinette (id)');
        $this->addSql('CREATE INDEX IDX_F3E399CEC93EA6F2 ON trotinette (model_trotinette_id)');
    }
}
