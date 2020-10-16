<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200930102643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ds.shift (start TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(), people_id INT, number SMALLINT NOT NULL, stop TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(start))');
        $this->addSql('CREATE INDEX IDX_B28C13803147C936 ON ds.shift (people_id)');
        $this->addSql('COMMENT ON TABLE ds.shift IS \'Смены\'');
        $this->addSql('ALTER TABLE ds.shift ADD CONSTRAINT FK_B28C13803147C936 FOREIGN KEY (people_id) REFERENCES ds.people (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE ds.shift');
    }
}
