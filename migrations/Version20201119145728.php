<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201119145728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ds.pocket_event (drec TIMESTAMP WITH TIME ZONE, type_id VARCHAR(1) NOT NULL, number_pocket SMALLINT NOT NULL, PRIMARY KEY(drec))');
        $this->addSql('CREATE INDEX IDX_43FFACC3C54C8C93 ON ds.pocket_event (type_id)');
        $this->addSql('COMMENT ON COLUMN ds.pocket_event.drec IS \'Время начала простоя\'');
        $this->addSql('COMMENT ON COLUMN ds.pocket_event.number_pocket IS \'Номер кармана\'');
        
        $this->addSql('CREATE TABLE ds.pocket_event_type (id VARCHAR(1) NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE ds.pocket_event_type IS \'Типи события для карманов\'');
        $this->addSql('COMMENT ON COLUMN ds.pocket_event_type.name IS \'Название типа\'');
        $this->addSql('ALTER TABLE ds.pocket_event ADD CONSTRAINT FK_43FFACC3C54C8C93 FOREIGN KEY (type_id) REFERENCES ds.pocket_event_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ds.pocket_event DROP CONSTRAINT FK_43FFACC3C54C8C93');
        $this->addSql('DROP TABLE ds.pocket_event');
        $this->addSql('DROP TABLE ds.pocket_event_type');
    }
}
