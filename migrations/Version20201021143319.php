<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201021143319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE ds.action_operator_id_seq CASCADE');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT board_nom_thickness_fkey');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT board_nom_width_fkey');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT board_nom_length_fkey');
        $this->addSql('ALTER TABLE ds.board DROP CONSTRAINT board_qual_list_id_fkey');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT board_nom_thickness_fkey FOREIGN KEY (nom_thickness) REFERENCES ds.thickness (nom) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT board_nom_width_fkey FOREIGN KEY (nom_width) REFERENCES ds.width (nom) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT board_nom_length_fkey FOREIGN KEY (nom_length) REFERENCES ds.length (value) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ds.board ADD CONSTRAINT board_qual_list_id_fkey FOREIGN KEY (qual_list_id) REFERENCES ds.quality_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
