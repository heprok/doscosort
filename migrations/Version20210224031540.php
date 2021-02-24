<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224031540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE ds.downtime_cause_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.downtime_place_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE ds.group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.quality_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql(
            'CREATE TABLE ds.break_shedule (
            start INT NOT NULL, 
            stop INT NOT NULL, 
            place_id INT NOT NULL, 
            cause_id INT NOT NULL, 
            CHECK ("start" != "stop"),
            PRIMARY KEY(start, stop))
            '
            );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_814608AE9F79558F ON ds.break_shedule (start)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_814608AEB95616B6 ON ds.break_shedule (stop)');
        $this->addSql('CREATE INDEX IDX_814608AEDA6A219 ON ds.break_shedule (place_id)');
        $this->addSql('CREATE INDEX IDX_814608AE66E2221E ON ds.break_shedule (cause_id)');
        $this->addSql('CREATE UNIQUE INDEX bredk_shedule_unique ON ds.break_shedule (start, stop)');

        $this->addSql('COMMENT ON TABLE ds.break_shedule IS \'График перерывов\'');
        $this->addSql('COMMENT ON COLUMN ds.break_shedule.start IS \'Начало перерыва в формате HHMM\'');
        $this->addSql('COMMENT ON COLUMN ds.break_shedule.stop IS \'Конец перерыва в формате HHMM\'');


        $this->addSql(
            'CREATE TABLE ds.downtime_group (
                id INT NOT NULL,
                text VARCHAR(128) NOT NULL,
                enabled BOOLEAN DEFAULT true NOT NULL,
                PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON TABLE ds.downtime_group IS \'Группы причин простоя\'');
        $this->addSql('COMMENT ON COLUMN ds.downtime_group.text IS \'Название причины\'');
        $this->addSql('COMMENT ON COLUMN ds.downtime_group.enabled IS \'Используется\'');

        $this->addSql(
            'CREATE TABLE ds.downtime_location (
                id INT NOT NULL,
                text VARCHAR(128) NOT NULL,
                enabled BOOLEAN DEFAULT true NOT NULL,
                PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON TABLE ds.downtime_location IS \'Локации простоя\'');
        $this->addSql('COMMENT ON COLUMN ds.downtime_location.text IS \'Название причины\'');
        $this->addSql('COMMENT ON COLUMN ds.downtime_location.enabled IS \'Используется\'');
        
        $this->addSql(
            'CREATE TABLE ds.duty (
                id CHAR(2) NOT NULL,
                name VARCHAR(30) NOT NULL,
                PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON TABLE ds.duty IS \'Список должностей\'');

        $this->addSql(
            'CREATE TABLE ds."group" (
                id smallint NOT NULL,
                species_id CHAR(2) NOT NULL,
                dry BOOLEAN NOT NULL,
                qualities INT NOT NULL,
                thickness SMALLINT NOT NULL,
                width SMALLINT NOT NULL,
                min_length SMALLINT NOT NULL,
                max_length SMALLINT NOT NULL,
                CHECK( qualities > 0 ),
                PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_7A476C00B2A1D860 ON ds."group" (species_id)');
        
        $this->addSql('COMMENT ON TABLE ds."group" IS \'Группы параметров досок\'');
        $this->addSql(
            'CREATE TABLE ds.people_to_duty (
                people_id INT NOT NULL,
                duty_id CHAR(2) NOT NULL,
                PRIMARY KEY(people_id, duty_id))'
        );
        $this->addSql('CREATE INDEX IDX_8956B4A93147C936 ON ds.people_to_duty (people_id)');
        $this->addSql('CREATE INDEX IDX_8956B4A93A1F9EC1 ON ds.people_to_duty (duty_id)');
        
        $this->addSql(
            'CREATE TABLE ds.quality (
                id SMALLINT NOT NULL,
                list_id SMALLINT NOT NULL,
                name VARCHAR(32) NOT NULL,
            PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_FF8A97663DAE168B ON ds.quality (list_id)');
        $this->addSql('COMMENT ON TABLE ds.quality IS \'Качества доски\'');
        $this->addSql('COMMENT ON COLUMN ds.quality.id IS \'ID доски, 1 бит\'');
        $this->addSql('COMMENT ON COLUMN ds.quality.name IS \'Название качества\'');
        
        $this->addSql(
            'CREATE TABLE ds.shift_shedule (
                start INT NOT NULL,
                stop INT NOT NULL,
                name VARCHAR(128) NOT NULL,
                PRIMARY KEY(start, stop))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_954776729F79558F ON ds.shift_shedule (start)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95477672B95616B6 ON ds.shift_shedule (stop)');
        $this->addSql('CREATE UNIQUE INDEX shift_shedule_unique ON ds.shift_shedule (start, stop)');

        $this->addSql('COMMENT ON TABLE ds.shift_shedule IS \'График сменов\'');
        $this->addSql('COMMENT ON COLUMN ds.shift_shedule.start IS \'Начало смены\'');
        $this->addSql('COMMENT ON COLUMN ds.shift_shedule.stop IS \'Конец смены\'');
        $this->addSql('COMMENT ON COLUMN ds.shift_shedule.name IS \'Наименование смены\'');
        
        $this->addSql(
            'CREATE TABLE ds.unload (drec_timestamp_key INT NOT NULL,
            group_id INT NOT NULL,
            qualities VARCHAR(255) NOT NULL,
            amount SMALLINT NOT NULL,
            PRIMARY KEY(drec_timestamp_key))'
        );
        $this->addSql('CREATE INDEX IDX_23747EEBFE54D947 ON ds.unload (group_id)');
        $this->addSql('COMMENT ON TABLE ds.unload IS \'Выгруженные карманы\'');
        $this->addSql('COMMENT ON COLUMN ds.unload.drec_timestamp_key IS \'Время выгрузки\'');
        $this->addSql('COMMENT ON COLUMN ds.unload.qualities IS \'Название качеств\'');
        $this->addSql('COMMENT ON COLUMN ds.unload.amount IS \'Количество досок\'');
        
        $this->addSql('ALTER TABLE ds.break_shedule ADD CONSTRAINT FK_814608AEDA6A219 FOREIGN KEY (place_id) REFERENCES ds.downtime_place (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.break_shedule ADD CONSTRAINT FK_814608AE66E2221E FOREIGN KEY (cause_id) REFERENCES ds.downtime_cause (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds."group" ADD CONSTRAINT FK_7A476C00B2A1D860 FOREIGN KEY (species_id) REFERENCES dic.species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.people_to_duty ADD CONSTRAINT FK_8956B4A93147C936 FOREIGN KEY (people_id) REFERENCES ds.people (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.people_to_duty ADD CONSTRAINT FK_8956B4A93A1F9EC1 FOREIGN KEY (duty_id) REFERENCES ds.duty (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.quality ADD CONSTRAINT FK_FF8A97663DAE168B FOREIGN KEY (list_id) REFERENCES ds.quality_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.unload ADD CONSTRAINT FK_23747EEBFE54D947 FOREIGN KEY (group_id) REFERENCES ds."group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('COMMENT ON COLUMN ds.action_operator.name IS \'Название действия\'');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT fk_4fd10382569eb619');
        $this->addSql('ALTER TABLE ds.board ADD quality_1_id SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.board ADD quality_2_id SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.board ADD quality_1_name VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE ds.board ADD quality_2_name VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE ds.board DROP qual_list_id');
        $this->addSql('ALTER TABLE ds.board DROP qualities');

        $this->addSql('COMMENT ON COLUMN ds.board.quality_1_id IS \'ID доски, 1 бит\'');
        $this->addSql('COMMENT ON COLUMN ds.board.quality_2_id IS \'ID доски, 2 бит\'');
        $this->addSql('COMMENT ON COLUMN ds.board.quality_1_name IS \'Название качества 1 операторa в момент записи\'');
        $this->addSql('COMMENT ON COLUMN ds.board.quality_2_name IS \'Название качества 2 операторa в момент записи\'');
        
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD103825F4720E6 FOREIGN KEY (quality_1_id) REFERENCES ds.quality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD103824DF28F08 FOREIGN KEY (quality_2_id) REFERENCES ds.quality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE INDEX IDX_4FD103825F4720E6 ON ds.board (quality_1_id)');
        $this->addSql('CREATE INDEX IDX_4FD103824DF28F08 ON ds.board (quality_2_id)');
        $this->addSql('ALTER INDEX ds.idx_4fd10382239d68ef RENAME TO IDX_4FD10382FAEEFDEE');
        $this->addSql('ALTER INDEX ds.idx_4fd10382b07643d2 RENAME TO IDX_4FD1038226E2D9BA');
        $this->addSql('ALTER INDEX ds.idx_4fd10382f51c341b RENAME TO IDX_4FD1038281B201E5');
        
        $this->addSql('COMMENT ON COLUMN ds.downtime.drec IS \'Время начала простоя\'');
        $this->addSql('COMMENT ON COLUMN ds.downtime.finish IS \'Время окончания простоя\'');

        $this->addSql('ALTER TABLE ds.downtime_cause ADD group_id INT NOT NULL');
        $this->addSql('ALTER TABLE ds.downtime_cause ADD enabled BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE ds.downtime_cause RENAME COLUMN name TO text');
        $this->addSql('COMMENT ON COLUMN ds.downtime_cause.enabled IS \'Используется\'');
        $this->addSql('ALTER TABLE ds.downtime_cause ADD CONSTRAINT FK_E2BA8CD5FE54D947 FOREIGN KEY (group_id) REFERENCES ds.downtime_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E2BA8CD5FE54D947 ON ds.downtime_cause (group_id)');

        $this->addSql('ALTER TABLE ds.downtime_place ADD location_id INT NOT NULL');
        $this->addSql('ALTER TABLE ds.downtime_place ADD enabled BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE ds.downtime_place RENAME COLUMN name TO text');
        $this->addSql('COMMENT ON COLUMN ds.downtime_place.enabled IS \'Используется\'');
        $this->addSql('ALTER TABLE ds.downtime_place ADD CONSTRAINT FK_667DA0A764D218E FOREIGN KEY (location_id) REFERENCES ds.downtime_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_667DA0A764D218E ON ds.downtime_place (location_id)');

        $this->addSql('COMMENT ON COLUMN ds.event.drec IS \'Начало события\'');
        $this->addSql('COMMENT ON COLUMN ds.people.fam IS \'Фамилия\'');
        $this->addSql('COMMENT ON COLUMN ds.people.nam IS \'Имя\'');
        $this->addSql('COMMENT ON COLUMN ds.people.pat IS \'Отчество\'');

        $this->addSql('COMMENT ON COLUMN ds.pocket_event.drec IS \'Время события\'');

        $this->addSql('ALTER TABLE ds.quality_list DROP def');

        $this->addSql('COMMENT ON COLUMN ds.shift.start IS \'Время начала смены\'');
        $this->addSql('COMMENT ON COLUMN ds.shift.stop IS \'Окончание смены\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ds.downtime_cause DROP CONSTRAINT FK_E2BA8CD5FE54D947');
        $this->addSql('ALTER TABLE ds.downtime_place DROP CONSTRAINT FK_667DA0A764D218E');
        $this->addSql('ALTER TABLE ds.people_to_duty DROP CONSTRAINT FK_8956B4A93A1F9EC1');
        $this->addSql('ALTER TABLE ds.unload DROP CONSTRAINT FK_23747EEBFE54D947');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT FK_4FD103825F4720E6');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT FK_4FD103824DF28F08');
        $this->addSql('DROP SEQUENCE ds.group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ds.quality_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE ds.downtime_cause_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ds.downtime_place_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE ds.break_shedule');
        $this->addSql('DROP TABLE ds.downtime_group');
        $this->addSql('DROP TABLE ds.downtime_location');
        $this->addSql('DROP TABLE ds.duty');
        $this->addSql('DROP TABLE ds."group"');
        $this->addSql('DROP TABLE ds.people_to_duty');
        $this->addSql('DROP TABLE ds.quality');
        $this->addSql('DROP TABLE ds.shift_shedule');
        $this->addSql('DROP TABLE ds.unload');
        $this->addSql('DROP INDEX IDX_E2BA8CD5FE54D947');
        $this->addSql('ALTER TABLE ds.downtime_cause DROP group_id');
        $this->addSql('ALTER TABLE ds.downtime_cause DROP enabled');
        $this->addSql('ALTER TABLE ds.downtime_cause RENAME COLUMN text TO name');
        $this->addSql('ALTER TABLE ds.downtime ALTER drec TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE ds.downtime ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('COMMENT ON COLUMN ds.downtime.drec IS NULL');
        $this->addSql('COMMENT ON COLUMN ds.downtime.finish IS NULL');
        $this->addSql('DROP INDEX IDX_667DA0A764D218E');
        $this->addSql('ALTER TABLE ds.downtime_place DROP location_id');
        $this->addSql('ALTER TABLE ds.downtime_place DROP enabled');
        $this->addSql('ALTER TABLE ds.downtime_place RENAME COLUMN text TO name');
        $this->addSql('COMMENT ON COLUMN ds.action_operator.name IS NULL');
        $this->addSql('COMMENT ON COLUMN ds.people.fam IS NULL');
        $this->addSql('COMMENT ON COLUMN ds.people.nam IS NULL');
        $this->addSql('COMMENT ON COLUMN ds.people.pat IS NULL');
        $this->addSql('ALTER TABLE ds.shift ALTER start TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE ds.shift ALTER start SET DEFAULT \'now()\'');
        $this->addSql('COMMENT ON COLUMN ds.shift.start IS NULL');
        $this->addSql('COMMENT ON COLUMN ds.shift.stop IS NULL');
        $this->addSql('ALTER TABLE ds.event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.event ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('COMMENT ON COLUMN ds.event.drec IS NULL');
        $this->addSql('DROP INDEX IDX_4FD103825F4720E6');
        $this->addSql('DROP INDEX IDX_4FD103824DF28F08');
        $this->addSql('ALTER TABLE ds.board ADD qual_list_id SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE ds.board ADD qualities CHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE ds.board DROP quality_1_id');
        $this->addSql('ALTER TABLE ds.board DROP quality_2_id');
        $this->addSql('ALTER TABLE ds.board DROP quality_1_name');
        $this->addSql('ALTER TABLE ds.board DROP quality_2_name');
        $this->addSql('ALTER TABLE ds.board ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.board ALTER drec DROP DEFAULT');
        $this->addSql('ALTER TABLE ds.board ALTER pocket TYPE CHAR(1)');
        $this->addSql('ALTER TABLE ds.board ALTER pocket DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN ds.board.qualities IS \'Два качества от операторов, по 4 бита\'');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT fk_4fd10382569eb619 FOREIGN KEY (qual_list_id) REFERENCES ds.quality_list (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4fd10382569eb619 ON ds.board (qual_list_id)');
        $this->addSql('ALTER INDEX ds.idx_4fd10382faeefdee RENAME TO idx_4fd10382239d68ef');
        $this->addSql('ALTER INDEX ds.idx_4fd1038226e2d9ba RENAME TO idx_4fd10382b07643d2');
        $this->addSql('ALTER INDEX ds.idx_4fd1038281b201e5 RENAME TO idx_4fd10382f51c341b');
        $this->addSql('ALTER TABLE ds.quality_list ADD def SMALLINT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN ds.quality_list.def IS \'ID качества по-умолчанию\'');
        $this->addSql('ALTER TABLE ds.pocket_event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.pocket_event ALTER drec DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN ds.pocket_event.drec IS \'Время начала простоя\'');
    }
}
