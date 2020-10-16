<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929070858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Список пород';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA dic');
        $this->addSql('CREATE TABLE dic.species (id CHAR(2) NOT NULL, name VARCHAR(25) NOT NULL, fir BOOLEAN NOT NULL, pers DOUBLE PRECISION DEFAULT NULL, hard_min SMALLINT DEFAULT NULL, hard_max SMALLINT DEFAULT NULL, dens_min SMALLINT DEFAULT NULL, dens_max SMALLINT DEFAULT NULL, enabled BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE dic.species IS \'Породы древисины\'');
        $this->addSql('COMMENT ON COLUMN dic.species.name IS \'Название\'');
        $this->addSql('COMMENT ON COLUMN dic.species.fir IS \'Хвойное\'');
        $this->addSql('COMMENT ON COLUMN dic.species.pers IS \'Устойчивость к гниению по DIN EN 350-2\'');
        $this->addSql('COMMENT ON COLUMN dic.species.hard_min IS \'Твёрдость по шкале Янка\'');
        $this->addSql('COMMENT ON COLUMN dic.species.dens_min IS \'Плотность кг/м³ при 15% влажности\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE dic.species');
    }
}
