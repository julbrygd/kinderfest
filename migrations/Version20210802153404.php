<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802153404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('DROP INDEX IDX_34DCD176833D8F43');
        $this->addSql('DROP INDEX IDX_34DCD17694F7ACF4');
        $this->addSql('DROP INDEX IDX_34DCD176C949F4D8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, start_punkt_id, start_zeit_id, registration_id, name, pre_name, adresse, plz, ort, email, tel FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_punkt_id INTEGER NOT NULL, start_zeit_id INTEGER DEFAULT NULL, registration_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, pre_name VARCHAR(255) NOT NULL COLLATE BINARY, adresse VARCHAR(255) NOT NULL COLLATE BINARY, plz INTEGER NOT NULL, ort VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, tel VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_34DCD176C949F4D8 FOREIGN KEY (start_punkt_id) REFERENCES start_punkt (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_34DCD17694F7ACF4 FOREIGN KEY (start_zeit_id) REFERENCES start_zeit (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_34DCD176833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO person (id, start_punkt_id, start_zeit_id, registration_id, name, pre_name, adresse, plz, ort, email, tel) SELECT id, start_punkt_id, start_zeit_id, registration_id, name, pre_name, adresse, plz, ort, email, tel FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
        $this->addSql('CREATE INDEX IDX_34DCD176833D8F43 ON person (registration_id)');
        $this->addSql('CREATE INDEX IDX_34DCD17694F7ACF4 ON person (start_zeit_id)');
        $this->addSql('CREATE INDEX IDX_34DCD176C949F4D8 ON person (start_punkt_id)');
        $this->addSql('DROP INDEX IDX_62A8A7A7890B6AF8');
        $this->addSql('DROP INDEX IDX_62A8A7A794F7ACF4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__registration AS SELECT id, start_zeit_id, start_punk_id, uuid FROM registration');
        $this->addSql('DROP TABLE registration');
        $this->addSql('CREATE TABLE registration (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_zeit_id INTEGER NOT NULL, start_punk_id INTEGER NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        , CONSTRAINT FK_62A8A7A794F7ACF4 FOREIGN KEY (start_zeit_id) REFERENCES start_zeit (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62A8A7A7890B6AF8 FOREIGN KEY (start_punk_id) REFERENCES start_punkt (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO registration (id, start_zeit_id, start_punk_id, uuid) SELECT id, start_zeit_id, start_punk_id, uuid FROM __temp__registration');
        $this->addSql('DROP TABLE __temp__registration');
        $this->addSql('CREATE INDEX IDX_62A8A7A7890B6AF8 ON registration (start_punk_id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A794F7ACF4 ON registration (start_zeit_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, roles CLOB NOT NULL --(DC2Type:json)
        , email VARCHAR(180) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, roles, password) SELECT id, username, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP INDEX IDX_34DCD176C949F4D8');
        $this->addSql('DROP INDEX IDX_34DCD17694F7ACF4');
        $this->addSql('DROP INDEX IDX_34DCD176833D8F43');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, start_punkt_id, start_zeit_id, registration_id, name, pre_name, adresse, plz, ort, email, tel FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_punkt_id INTEGER NOT NULL, start_zeit_id INTEGER DEFAULT NULL, registration_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, pre_name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, plz INTEGER NOT NULL, ort VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO person (id, start_punkt_id, start_zeit_id, registration_id, name, pre_name, adresse, plz, ort, email, tel) SELECT id, start_punkt_id, start_zeit_id, registration_id, name, pre_name, adresse, plz, ort, email, tel FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
        $this->addSql('CREATE INDEX IDX_34DCD176C949F4D8 ON person (start_punkt_id)');
        $this->addSql('CREATE INDEX IDX_34DCD17694F7ACF4 ON person (start_zeit_id)');
        $this->addSql('CREATE INDEX IDX_34DCD176833D8F43 ON person (registration_id)');
        $this->addSql('DROP INDEX IDX_62A8A7A794F7ACF4');
        $this->addSql('DROP INDEX IDX_62A8A7A7890B6AF8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__registration AS SELECT id, start_zeit_id, start_punk_id, uuid FROM registration');
        $this->addSql('DROP TABLE registration');
        $this->addSql('CREATE TABLE registration (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_zeit_id INTEGER NOT NULL, start_punk_id INTEGER NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        )');
        $this->addSql('INSERT INTO registration (id, start_zeit_id, start_punk_id, uuid) SELECT id, start_zeit_id, start_punk_id, uuid FROM __temp__registration');
        $this->addSql('DROP TABLE __temp__registration');
        $this->addSql('CREATE INDEX IDX_62A8A7A794F7ACF4 ON registration (start_zeit_id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A7890B6AF8 ON registration (start_punk_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO user (id, username, roles, password) SELECT id, username, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
