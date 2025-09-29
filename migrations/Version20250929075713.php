<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929075713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trottinette_accessory (trottinette_id INT NOT NULL, accessory_id INT NOT NULL, INDEX IDX_B37F755EF6798F43 (trottinette_id), INDEX IDX_B37F755E27E8CC78 (accessory_id), PRIMARY KEY(trottinette_id, accessory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755EF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trottinette_accessory ADD CONSTRAINT FK_B37F755E27E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755EF6798F43');
        $this->addSql('ALTER TABLE trottinette_accessory DROP FOREIGN KEY FK_B37F755E27E8CC78');
        $this->addSql('DROP TABLE trottinette_accessory');
    }
}
