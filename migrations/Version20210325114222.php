<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325114222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ds.unload DROP CONSTRAINT fk_23747eebfe54d947');

        $this->addSql('CREATE TYPE ds."leam" AS (length smallint, amount smallint)');
        $this->addSql('COMMENT ON TYPE ds."leam" IS \'Длины и их кол-во\'');
        $this->addSql('COMMENT ON COLUMN ds."leam"."length" IS \'Длина\'');
        $this->addSql('COMMENT ON COLUMN ds."leam"."amount" IS \'Группы параметров досок\'');

        $this->addSql('CREATE SEQUENCE ds.downtime_cause_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.downtime_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.downtime_location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.downtime_place_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.package_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.package_location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE ds."group"');
        $this->addSql('CREATE TABLE ds."group" (
                id INT NOT NULL,
                species_id CHAR(2) NOT NULL,
                dry BOOLEAN NOT NULL,
                qualities INT NOT NULL,
                thickness SMALLINT NOT NULL,
                width SMALLINT NOT NULL,
                min_length SMALLINT NOT NULL,
                max_length SMALLINT NOT NULL,
                PRIMARY KEY(id))
        ');
        $this->addSql('CREATE INDEX IDX_7A476C00B2A1D860 ON ds."group" (species_id)');
        $this->addSql('COMMENT ON TABLE ds."group" IS \'Группы параметров досок\'');
        $this->addSql(
                'CREATE TABLE ds.package (
                id INT NOT NULL,
                species_id CHAR(2) DEFAULT NULL,
                drec TIMESTAMP(0) WITH TIME ZONE NOT NULL,
                thickness SMALLINT DEFAULT NULL,
                width SMALLINT DEFAULT NULL,
                qualities VARCHAR(32) DEFAULT NULL,
                boards ds."leam"[] NOT NULL,
                dry BOOLEAN NOT NULL,
                PRIMARY KEY(id))
        ');
        $this->addSql('CREATE INDEX IDX_5D50FBE3B2A1D860 ON ds.package (species_id)');
        $this->addSql('COMMENT ON TABLE ds.package IS \'Сформированные пакеты\'');
        $this->addSql('COMMENT ON COLUMN ds.package.drec IS \'Время формирования пакета\'');
        $this->addSql('COMMENT ON COLUMN ds.package.thickness IS \'Толщина\'');
        $this->addSql('COMMENT ON COLUMN ds.package.width IS \'Ширина\'');
        $this->addSql('COMMENT ON COLUMN ds.package.qualities IS \'Качество\'');
        $this->addSql('COMMENT ON COLUMN ds.package.boards IS \'Массив досок\'');
        $this->addSql('COMMENT ON COLUMN ds.package.dry IS \'Сухая или сырая\'');
        $this->addSql(
                'CREATE TABLE ds.package_location (
                id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON TABLE ds.package_location IS \'Локация пакета\'');
        $this->addSql('COMMENT ON COLUMN ds.package_location.name IS \'Название\'');
        $this->addSql('CREATE TABLE ds.standard_length (
                standard SMALLINT NOT NULL,
                minimum SMALLINT NOT NULL,
                maximum SMALLINT NOT NULL,
                PRIMARY KEY(standard))
        ');
        $this->addSql('COMMENT ON TABLE ds.standard_length IS \'Cтандартные длины\'');
        $this->addSql('COMMENT ON COLUMN ds.standard_length.minimum IS \'Минимальная граница диапзаона не включая, мм\'');
        $this->addSql('COMMENT ON COLUMN ds.standard_length.maximum IS \'Максимальная граница диапзаона не включая, мм\'');
        $this->addSql('CREATE TABLE ds.vars (
                name VARCHAR(64) NOT NULL,
                value VARCHAR(64) DEFAULT NULL,
                PRIMARY KEY(name))
        ');
        $this->addSql('COMMENT ON COLUMN ds.vars.name IS \'Ключ\'');
        $this->addSql('COMMENT ON COLUMN ds.vars.value IS \'Значение\'');
        $this->addSql('ALTER TABLE ds."group" ADD CONSTRAINT FK_7A476C00B2A1D860 FOREIGN KEY (species_id) REFERENCES dic.species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.package ADD CONSTRAINT FK_5D50FBE3B2A1D860 FOREIGN KEY (species_id) REFERENCES dic.species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT fk_4fd103825f4720e6');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT fk_4fd103824df28f08');
        $this->addSql('ALTER TABLE ds.board ADD quality_2 SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.board DROP quality_2_id');
        $this->addSql('ALTER TABLE ds.board RENAME COLUMN quality_1_id TO quality_1');
        $this->addSql('COMMENT ON COLUMN ds.board.quality_2 IS \'ID доски, 1 бит\'');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382F034E435 FOREIGN KEY (quality_1) REFERENCES ds.quality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382693DB58F FOREIGN KEY (quality_2) REFERENCES ds.quality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FD10382F034E435 ON ds.board (quality_1)');
        $this->addSql('CREATE INDEX IDX_4FD10382693DB58F ON ds.board (quality_2)');
        $this->addSql('ALTER TABLE ds.event ADD code SMALLINT NOT NULL');

        $this->addSql('ALTER TABLE ds.unload ADD drec timestamp with time zone UNIQUE NOT NULL DEFAULT "now"()');
        $this->addSql('ALTER TABLE ds.unload ADD pocket SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.unload ADD volume DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE ds.unload DROP drec_timestamp_key');
        $this->addSql('ALTER TABLE ds.unload RENAME COLUMN group_id TO "group"');
        $this->addSql('COMMENT ON COLUMN ds.unload.drec IS \'Время выгрузки\'');
        $this->addSql('COMMENT ON COLUMN ds.unload.pocket IS \'Карман\'');
        $this->addSql('COMMENT ON COLUMN ds.unload.volume IS \'Объём выгруженного кармана\'');

        $this->addSql('ALTER TABLE ds.unload ADD CONSTRAINT FK_23747EEB6DC044C5 FOREIGN KEY ("group") REFERENCES ds."group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_23747EEB6DC044C5 ON ds.unload ("group")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ds.unload DROP CONSTRAINT FK_23747EEB6DC044C5');
        $this->addSql('ALTER TABLE ds.package_move DROP CONSTRAINT FK_EFA166636044248D');
        $this->addSql('ALTER TABLE ds.package_move DROP CONSTRAINT FK_EFA16663E3E525FE');
        $this->addSql('DROP SEQUENCE ds.downtime_cause_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.downtime_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.downtime_location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.downtime_place_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.package_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.package_location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.package_move_id_seq CASCADE');
        $this->addSql('CREATE TABLE ds."group" (id SMALLINT NOT NULL,
                species_id CHAR(2) NOT NULL, dry BOOLEAN NOT NULL, qualities INT NOT NULL, thickness SMALLINT NOT NULL, width SMALLINT NOT NULL, min_length SMALLINT NOT NULL, max_length SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7a476c00b2a1d860 ON ds."group" (species_id)');
        $this->addSql('COMMENT ON TABLE ds."group" IS \'Группы параметров досок\'');
        $this->addSql('ALTER TABLE ds."group" ADD CONSTRAINT fk_7a476c00b2a1d860 FOREIGN KEY (species_id) REFERENCES dic.species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE ds."group"');
        $this->addSql('DROP TABLE ds.package');
        $this->addSql('DROP TABLE ds.package_location');
        $this->addSql('DROP TABLE ds.package_move');
        $this->addSql('DROP TABLE ds.standard_length');
        $this->addSql('DROP TABLE ds.vars');
        $this->addSql('ALTER TABLE ds.downtime ALTER drec TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE ds.downtime ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.shift ALTER start TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE ds.shift ALTER start SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.event DROP code');
        $this->addSql('ALTER TABLE ds.event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.event ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.pocket_event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.pocket_event ALTER drec DROP DEFAULT');
        $this->addSql('DROP INDEX shift_shedule_pkey');
        $this->addSql('CREATE UNIQUE INDEX uniq_954776729f79558f ON ds.shift_shedule (start)');
        $this->addSql('ALTER TABLE ds.shift_shedule ADD PRIMARY KEY (start, stop)');
        $this->addSql('DROP INDEX break_shedule_pkey');
        $this->addSql('CREATE UNIQUE INDEX uniq_814608ae9f79558f ON ds.break_shedule (start)');
        $this->addSql('ALTER TABLE ds.break_shedule ADD PRIMARY KEY (start, stop)');
        $this->addSql('DROP INDEX IDX_23747EEB6DC044C5');
        $this->addSql('DROP INDEX unload_pkey');
        $this->addSql('ALTER TABLE ds.unload ADD drec_timestamp_key INT NOT NULL');
        $this->addSql('ALTER TABLE ds.unload DROP drec');
        $this->addSql('ALTER TABLE ds.unload DROP pocket');
        $this->addSql('ALTER TABLE ds.unload DROP volume');
        $this->addSql('ALTER TABLE ds.unload RENAME COLUMN "group" TO group_id');
        $this->addSql('COMMENT ON COLUMN ds.unload.drec_timestamp_key IS \'Время выгрузки\'');
        $this->addSql('ALTER TABLE ds.unload ADD CONSTRAINT fk_23747eebfe54d947 FOREIGN KEY (group_id) REFERENCES ds."group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_23747eebfe54d947 ON ds.unload (group_id)');
        $this->addSql('ALTER TABLE ds.unload ADD PRIMARY KEY (drec_timestamp_key)');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT FK_4FD10382F034E435');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT FK_4FD10382693DB58F');
        $this->addSql('DROP INDEX IDX_4FD10382F034E435');
        $this->addSql('DROP INDEX IDX_4FD10382693DB58F');
        $this->addSql('ALTER TABLE ds.board ADD quality_1_id SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.board ADD quality_2_id SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.board DROP quality_1');
        $this->addSql('ALTER TABLE ds.board DROP quality_2');
        $this->addSql('ALTER TABLE ds.board ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.board ALTER drec DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN ds.board.quality_1_id IS \'ID доски, 1 бит\'');
        $this->addSql('COMMENT ON COLUMN ds.board.quality_2_id IS \'ID доски, 2 бит\'');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT fk_4fd103825f4720e6 FOREIGN KEY (quality_1_id) REFERENCES ds.quality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT fk_4fd103824df28f08 FOREIGN KEY (quality_2_id) REFERENCES ds.quality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4fd103824df28f08 ON ds.board (quality_2_id)');
        $this->addSql('CREATE INDEX idx_4fd103825f4720e6 ON ds.board (quality_1_id)');
    }
}
