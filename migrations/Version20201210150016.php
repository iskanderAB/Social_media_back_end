<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210150016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat ADD nmr INT DEFAULT NULL, ADD year_recruitment DATE DEFAULT NULL, ADD duree INT DEFAULT NULL, ADD poste VARCHAR(255) DEFAULT NULL, ADD company VARCHAR(255) DEFAULT NULL, ADD company_skills VARCHAR(255) DEFAULT NULL, ADD your_skills VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stat DROP nmr, DROP year_recruitment, DROP duree, DROP poste, DROP company, DROP company_skills, DROP your_skills');
    }
}
