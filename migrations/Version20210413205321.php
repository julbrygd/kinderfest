<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413205321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_punkt_id INTEGER NOT NULL, start_zeit_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, pre_name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, plz INTEGER NOT NULL, ort VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_34DCD176C949F4D8 ON person (start_punkt_id)');
        $this->addSql('CREATE INDEX IDX_34DCD17694F7ACF4 ON person (start_zeit_id)');
        $this->addSql('CREATE TABLE start_punkt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, long DOUBLE PRECISION NOT NULL, lat DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE start_zeit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, zeit TIME NOT NULL, max_personen INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE start_punkt');
        $this->addSql('DROP TABLE start_zeit');
        $this->addSql('DROP TABLE user');
    }
}
