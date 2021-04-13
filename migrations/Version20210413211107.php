<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413211107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_34DCD17694F7ACF4');
        $this->addSql('DROP INDEX IDX_34DCD176C949F4D8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, start_punkt_id, start_zeit_id, name, pre_name, adresse, plz, ort, email, tel FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_punkt_id INTEGER NOT NULL, start_zeit_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, pre_name VARCHAR(255) NOT NULL COLLATE BINARY, adresse VARCHAR(255) NOT NULL COLLATE BINARY, plz INTEGER NOT NULL, ort VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, tel VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_34DCD176C949F4D8 FOREIGN KEY (start_punkt_id) REFERENCES start_punkt (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_34DCD17694F7ACF4 FOREIGN KEY (start_zeit_id) REFERENCES start_zeit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO person (id, start_punkt_id, start_zeit_id, name, pre_name, adresse, plz, ort, email, tel) SELECT id, start_punkt_id, start_zeit_id, name, pre_name, adresse, plz, ort, email, tel FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
        $this->addSql('CREATE INDEX IDX_34DCD17694F7ACF4 ON person (start_zeit_id)');
        $this->addSql('CREATE INDEX IDX_34DCD176C949F4D8 ON person (start_punkt_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__start_punkt AS SELECT id, name FROM start_punkt');
        $this->addSql('DROP TABLE start_punkt');
        $this->addSql('CREATE TABLE start_punkt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, iframe CLOB NOT NULL)');
        $this->addSql('INSERT INTO start_punkt (id, name) SELECT id, name FROM __temp__start_punkt');
        $this->addSql('DROP TABLE __temp__start_punkt');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_34DCD176C949F4D8');
        $this->addSql('DROP INDEX IDX_34DCD17694F7ACF4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, start_punkt_id, start_zeit_id, name, pre_name, adresse, plz, ort, email, tel FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_punkt_id INTEGER NOT NULL, start_zeit_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, pre_name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, plz INTEGER NOT NULL, ort VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO person (id, start_punkt_id, start_zeit_id, name, pre_name, adresse, plz, ort, email, tel) SELECT id, start_punkt_id, start_zeit_id, name, pre_name, adresse, plz, ort, email, tel FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
        $this->addSql('CREATE INDEX IDX_34DCD176C949F4D8 ON person (start_punkt_id)');
        $this->addSql('CREATE INDEX IDX_34DCD17694F7ACF4 ON person (start_zeit_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__start_punkt AS SELECT id, name FROM start_punkt');
        $this->addSql('DROP TABLE start_punkt');
        $this->addSql('CREATE TABLE start_punkt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, long DOUBLE PRECISION NOT NULL, lat DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO start_punkt (id, name) SELECT id, name FROM __temp__start_punkt');
        $this->addSql('DROP TABLE __temp__start_punkt');
    }
}
