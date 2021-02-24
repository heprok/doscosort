<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022080333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE ds.board (
                drec TIMESTAMP WITH TIME ZONE,
                nom_thickness SMALLINT NOT NULL,
                nom_width SMALLINT NOT NULL,
                nom_length SMALLINT NOT NULL,
                qual_list_id SMALLINT NOT NULL,
                species_id CHAR(2) NOT NULL,
                thickness SMALLINT NOT NULL,
                width SMALLINT NOT NULL,
                length SMALLINT NOT NULL,
                qualities CHAR NOT NULL,
                pocket SMALLINT NOT NULL,
                PRIMARY KEY(drec))'
        );
        $this->addSql('CREATE INDEX IDX_4FD10382239D68EF ON ds.board (nom_thickness)');
        $this->addSql('CREATE INDEX IDX_4FD10382B07643D2 ON ds.board (nom_width)');
        $this->addSql('CREATE INDEX IDX_4FD10382F51C341B ON ds.board (nom_length)');
        $this->addSql('CREATE INDEX IDX_4FD10382569EB619 ON ds.board (qual_list_id)');
        $this->addSql('CREATE INDEX IDX_4FD10382B2A1D860 ON ds.board (species_id)');

        $this->addSql('COMMENT ON COLUMN ds.board.drec IS \'Дата записи\'');
        $this->addSql('COMMENT ON COLUMN ds.board.nom_thickness IS \'Номинальная толщина\'');
        $this->addSql('COMMENT ON COLUMN ds.board.nom_width IS \'Номинальная ширина\'');
        $this->addSql('COMMENT ON COLUMN ds.board.nom_length IS \'Длина\'');
        $this->addSql('COMMENT ON COLUMN ds.board.thickness IS \'Пильная толщина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN ds.board.width IS \'Пильная ширина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN ds.board.length IS \'Пильная длина доски, мм.\'');
        $this->addSql('COMMENT ON COLUMN ds.board.qualities IS \'Два качества от операторов, по 4 бита\'');
        $this->addSql('COMMENT ON COLUMN ds.board.pocket IS \'Карман\'');

        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382239D68EF FOREIGN KEY (nom_thickness) REFERENCES ds.thickness (nom) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382B07643D2 FOREIGN KEY (nom_width) REFERENCES ds.width (nom) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382F51C341B FOREIGN KEY (nom_length) REFERENCES ds.length (value) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382569EB619 FOREIGN KEY (qual_list_id) REFERENCES ds.quality_list (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT FK_4FD10382B2A1D860 FOREIGN KEY (species_id) REFERENCES dic.species (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE ds.board');
    }
}
