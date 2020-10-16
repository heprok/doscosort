<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014140752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE ds.length (
                value SMALLINT NOT NULL, 
            PRIMARY KEY(value))');
        $this->addSql('COMMENT ON TABLE ds.length IS \'Справочник длин\'');
        $this->addSql('COMMENT ON COLUMN ds.length.value IS \'Длина\'');
        
        $this->addSql('CREATE TABLE ds.quality_list (
                id SMALLINT NOT NULL, 
                name VARCHAR(32) NOT NULL, 
                def SMALLINT DEFAULT NULL, 
            PRIMARY KEY(id))');

        $this->addSql('COMMENT ON TABLE ds.quality_list IS \'Списки качеств\'');
        $this->addSql('COMMENT ON COLUMN ds.quality_list.name IS \'Название списка\'');
        $this->addSql('COMMENT ON COLUMN ds.quality_list.def IS \'ID качества по-умолчанию\'');

        $this->addSql('CREATE TABLE ds.thickness (
                nom SMALLINT NOT NULL, 
                min SMALLINT NOT NULL, 
                max SMALLINT NOT NULL, 
            PRIMARY KEY(nom))');

        $this->addSql('COMMENT ON TABLE ds.thickness IS \'Справочник толщин\'');
        $this->addSql('COMMENT ON COLUMN ds.thickness.nom IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN ds.thickness.min IS \'Минимальная толщина\'');
        $this->addSql('COMMENT ON COLUMN ds.thickness.max IS \'Максимальная толщина\'');

        $this->addSql('CREATE TABLE ds.width (
                nom SMALLINT NOT NULL, 
                min SMALLINT NOT NULL, 
                max SMALLINT NOT NULL, 
            PRIMARY KEY(nom))');

        $this->addSql('COMMENT ON TABLE ds.width IS \'Справочник ширин\'');
        $this->addSql('COMMENT ON COLUMN ds.width.nom IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN ds.width.min IS \'Минимальная ширина\'');
        $this->addSql('COMMENT ON COLUMN ds.width.max IS \'Максимальная ширина\'');

        $this->addSql('CREATE TABLE ds.board (
                drec TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                thickness SMALLINT NOT NULL, 
                width SMALLINT NOT NULL, 
                length SMALLINT NOT NULL, 
                nom_thickness SMALLINT NOT NULL REFERENCES ds."thickness"( nom ), 
                nom_width SMALLINT NOT NULL REFERENCES ds."width"( nom ), 
                nom_length SMALLINT NOT NULL REFERENCES ds."length"( value ), 
                qual_list_id SMALLINT NOT NULL REFERENCES ds."quality_list"( id ), 
                qualities CHAR NOT NULL, 
                pocket CHAR NOT NULL, 
        PRIMARY KEY(drec))');

    $this->addSql('COMMENT ON COLUMN ds.board.drec IS \'Дата записи\'');
    $this->addSql('COMMENT ON COLUMN ds.board.thickness IS \'Пильная толщина доски, мм.\'');
    $this->addSql('COMMENT ON COLUMN ds.board.width IS \'Пильная ширина доски, мм.\'');
    $this->addSql('COMMENT ON COLUMN ds.board.length IS \'Пильная длина доски, мм.\'');
    $this->addSql('COMMENT ON COLUMN ds.board.nom_thickness IS \'Номинальная толщина доски, мм.\'');
    $this->addSql('COMMENT ON COLUMN ds.board.nom_width IS \'Номинальная ширина доски, мм.\'');
    $this->addSql('COMMENT ON COLUMN ds.board.nom_length IS \'Номинальная длина доски, мм.\'');
    $this->addSql('COMMENT ON COLUMN ds.board.qual_list_id IS \'ID списка качеств\'');
    $this->addSql('COMMENT ON COLUMN ds.board.qualities IS \'Два качества от операторов, по 4 бита\'');
    $this->addSql('COMMENT ON COLUMN ds.board.pocket IS \'Карман\'');
    
    $this->addSql('CREATE SEQUENCE ds.quality_list_id_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 32767 NO CYCLE START 1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE ds.board');
        $this->addSql('DROP TABLE ds.length');
        $this->addSql('DROP TABLE ds.quality_list');
        $this->addSql('DROP TABLE ds.thickness');
        $this->addSql('DROP TABLE ds.width');
    }
}
