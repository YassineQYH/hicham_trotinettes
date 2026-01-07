<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260107143346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE home_video (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, video_file VARCHAR(255) DEFAULT NULL, video_url VARCHAR(255) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trottinette DROP is_header, DROP header_image, DROP header_btn_title');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE first_name first_name VARCHAR(64) NOT NULL, CHANGE last_name last_name VARCHAR(64) NOT NULL, CHANGE tel tel VARCHAR(16) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE home_video');
        $this->addSql('ALTER TABLE trottinette ADD is_header TINYINT(1) NOT NULL, ADD header_image VARCHAR(255) DEFAULT NULL, ADD header_btn_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tel tel VARCHAR(16) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
