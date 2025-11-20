<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120155512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD112469DE2');
        $this->addSql('DROP INDEX IDX_C11D7DD112469DE2 ON promotion');
        $this->addSql('ALTER TABLE promotion CHANGE target_type target_type VARCHAR(30) NOT NULL, CHANGE category_id category_access_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD1888AB5FF FOREIGN KEY (category_access_id) REFERENCES category_accessory (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD1888AB5FF ON promotion (category_access_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD1888AB5FF');
        $this->addSql('DROP INDEX IDX_C11D7DD1888AB5FF ON promotion');
        $this->addSql('ALTER TABLE promotion CHANGE target_type target_type VARCHAR(20) NOT NULL, CHANGE category_access_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD112469DE2 FOREIGN KEY (category_id) REFERENCES category_accessory (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD112469DE2 ON promotion (category_id)');
    }
}
