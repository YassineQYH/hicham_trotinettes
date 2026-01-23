<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260123084635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracteristique ADD CONSTRAINT FK_D14FBE8BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_caracteristique (id)');
        $this->addSql('CREATE INDEX IDX_D14FBE8BBCF5E72D ON caracteristique (categorie_id)');
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP FOREIGN KEY FK_22FC340CBCF5E72D');
        $this->addSql('DROP INDEX IDX_22FC340CBCF5E72D ON trottinette_caracteristique');
        $this->addSql('ALTER TABLE trottinette_caracteristique DROP categorie_id, CHANGE trottinette_id trottinette_id INT NOT NULL, CHANGE caracteristique_id caracteristique_id INT NOT NULL');
        $this->addSql('ALTER TABLE trottinette_description_section DROP FOREIGN KEY FK_B92E215BF6798F43');
        $this->addSql('ALTER TABLE trottinette_description_section CHANGE content content LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE trottinette_description_section ADD CONSTRAINT FK_B92E215BF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracteristique DROP FOREIGN KEY FK_D14FBE8BBCF5E72D');
        $this->addSql('DROP INDEX IDX_D14FBE8BBCF5E72D ON caracteristique');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD categorie_id INT DEFAULT NULL, CHANGE trottinette_id trottinette_id INT DEFAULT NULL, CHANGE caracteristique_id caracteristique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trottinette_caracteristique ADD CONSTRAINT FK_22FC340CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_caracteristique (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_22FC340CBCF5E72D ON trottinette_caracteristique (categorie_id)');
        $this->addSql('ALTER TABLE trottinette_description_section DROP FOREIGN KEY FK_B92E215BF6798F43');
        $this->addSql('ALTER TABLE trottinette_description_section CHANGE content content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE trottinette_description_section ADD CONSTRAINT FK_B92E215BF6798F43 FOREIGN KEY (trottinette_id) REFERENCES trottinette (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
