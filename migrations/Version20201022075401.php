<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022075401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE ds.length (
                value SMALLINT NOT NULL, 
                PRIMARY KEY(value))'
        );

        $this->addSql('COMMENT ON TABLE ds.length IS \'Справочник длин\'');
        $this->addSql('COMMENT ON COLUMN ds.length.value IS \'Длина\'');

        $this->addSql(
            'CREATE TABLE ds.quality_list (
                id SMALLINT NOT NULL,
                name VARCHAR(32) NOT NULL,
                def SMALLINT DEFAULT NULL,
                PRIMARY KEY(id))'
        );
        $this->addSql('CREATE SEQUENCE ds.quality_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        
        $this->addSql('COMMENT ON TABLE ds.quality_list IS \'Списки качеств\'');
        $this->addSql('COMMENT ON COLUMN ds.quality_list.name IS \'Название списка\'');
        $this->addSql('COMMENT ON COLUMN ds.quality_list.def IS \'ID качества по-умолчанию\'');

        $this->addSql(
            'CREATE TABLE ds.thickness (
                nom SMALLINT NOT NULL,
                min SMALLINT NOT NULL,
                max SMALLINT NOT NULL,
                CHECK ( min <= max ), 
                PRIMARY KEY(nom))'
        );
        $this->addSql('COMMENT ON TABLE ds.thickness IS \'Справочник толщин\'');
        $this->addSql('COMMENT ON COLUMN ds.thickness.nom IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN ds.thickness.min IS \'Минимальная толщина\'');
        $this->addSql('COMMENT ON COLUMN ds.thickness.max IS \'Максимальная толщина\'');

        $this->addSql(
            'CREATE TABLE ds.width (nom SMALLINT NOT NULL,
                min SMALLINT NOT NULL,
                max SMALLINT NOT NULL,
                CHECK ( min <= max ), 
                PRIMARY KEY(nom))');

        $this->addSql('COMMENT ON TABLE ds.width IS \'Справочник ширин\'');
        $this->addSql('COMMENT ON COLUMN ds.width.nom IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN ds.width.min IS \'Минимальная ширина\'');
        $this->addSql('COMMENT ON COLUMN ds.width.max IS \'Максимальная ширина\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');

        $this->addSql('DROP TABLE ds.length');
        $this->addSql('DROP TABLE ds.quality_list');
        $this->addSql('DROP TABLE ds.thickness');
        $this->addSql('DROP TABLE ds.width');
    }
}
