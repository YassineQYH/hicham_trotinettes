<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929084045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trottinette_description_section (id INT AUTO_INCREMENT NOT NULL, trottinette_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, section_order INT NOT NULL, INDEX IDX_B92E215BF6798F43 (trottinette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trottinette_description_section ADD CONSTRAINT FK_B92E215BF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trottinette_description_section DROP FOREIGN KEY FK_B92E215BF6798F43');
        $this->addSql('DROP TABLE trottinette_description_section');
    }
}
