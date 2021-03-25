<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325140649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('COMMENT ON TABLE ds."group" IS \'Группы параметров досок\'');
        $this->addSql('CREATE TABLE ds.package_move (
                drec VARCHAR(255) NOT NULL,
                src INT NOT NULL,
                dst INT NOT NULL,
                package_id INT NOT NULL,
                comment VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(drec))'
        );
        $this->addSql('CREATE INDEX IDX_EFA166636044248D ON ds.package_move (src)');
        $this->addSql('CREATE INDEX IDX_EFA16663E3E525FE ON ds.package_move (dst)');
        $this->addSql('CREATE INDEX IDX_EFA16663F44CABFF ON ds.package_move (package_id)');
        $this->addSql('COMMENT ON TABLE ds.package_move IS \'Информация о передвежениях\'');
        $this->addSql('COMMENT ON COLUMN ds.package_move.drec IS \'Время передвежения пакета\'');
        $this->addSql('ALTER TABLE ds.package_move ADD CONSTRAINT FK_EFA166636044248D FOREIGN KEY (src) REFERENCES ds.package_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.package_move ADD CONSTRAINT FK_EFA16663E3E525FE FOREIGN KEY (dst) REFERENCES ds.package_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.package_move ADD CONSTRAINT FK_EFA16663F44CABFF FOREIGN KEY (package_id) REFERENCES ds.package (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ds.unload DROP CONSTRAINT FK_23747EEB6DC044C5');
        $this->addSql('CREATE TABLE ds."group" (id INT NOT NULL,
         species_id CHAR(2) NOT NULL, dry BOOLEAN NOT NULL, qualities INT NOT NULL, thickness SMALLINT NOT NULL, width SMALLINT NOT NULL, min_length SMALLINT NOT NULL, max_length SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7a476c00b2a1d860 ON ds."group" (species_id)');
        $this->addSql('COMMENT ON TABLE ds."group" IS \'Группы параметров досок\'');
        $this->addSql('ALTER TABLE ds."group" ADD CONSTRAINT fk_7a476c00b2a1d860 FOREIGN KEY (species_id) REFERENCES dic.species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE ds."group"');
        $this->addSql('DROP TABLE ds.package_move');
        $this->addSql('ALTER TABLE ds.event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.event ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.downtime ALTER drec TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE ds.downtime ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.shift ALTER start TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE ds.shift ALTER start SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.pocket_event ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.pocket_event ALTER drec DROP DEFAULT');
        $this->addSql('DROP INDEX shift_shedule_pkey');
        $this->addSql('CREATE UNIQUE INDEX uniq_954776729f79558f ON ds.shift_shedule (start)');
        $this->addSql('ALTER TABLE ds.shift_shedule ADD PRIMARY KEY (start, stop)');
        $this->addSql('DROP INDEX break_shedule_pkey');
        $this->addSql('CREATE UNIQUE INDEX uniq_814608ae9f79558f ON ds.break_shedule (start)');
        $this->addSql('ALTER TABLE ds.break_shedule ADD PRIMARY KEY (start, stop)');
        $this->addSql('ALTER TABLE ds.package ALTER boards TYPE leam');
        $this->addSql('ALTER TABLE ds.package ALTER boards DROP DEFAULT');
        $this->addSql('ALTER TABLE ds.board ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.board ALTER drec DROP DEFAULT');
        $this->addSql('CREATE INDEX idx_4fd103825f4720e6 ON ds.board (quality_1)');
        $this->addSql('ALTER TABLE ds.unload DROP CONSTRAINT fk_23747eeb6dc044c5');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE ds.unload ALTER drec TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE ds.unload ALTER drec SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE ds.unload ADD CONSTRAINT fk_23747eeb6dc044c5 FOREIGN KEY ("group") REFERENCES ds."group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX unload_drec_key ON ds.unload (drec)');
        $this->addSql('CREATE INDEX idx_23747eebfe54d947 ON ds.unload ("group")');
    }
}
