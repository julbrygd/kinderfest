<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806072633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, start_punkt_id INT NOT NULL, start_zeit_id INT DEFAULT NULL, registration_id INT NOT NULL, name VARCHAR(255) NOT NULL, pre_name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, plz INT NOT NULL, ort VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, INDEX IDX_34DCD176C949F4D8 (start_punkt_id), INDEX IDX_34DCD17694F7ACF4 (start_zeit_id), INDEX IDX_34DCD176833D8F43 (registration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, start_zeit_id INT NOT NULL, start_punk_id INT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, INDEX IDX_62A8A7A794F7ACF4 (start_zeit_id), INDEX IDX_62A8A7A7890B6AF8 (start_punk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE start_punkt (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, iframe LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE start_zeit (id INT AUTO_INCREMENT NOT NULL, zeit TIME NOT NULL, max_personen INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(180) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176C949F4D8 FOREIGN KEY (start_punkt_id) REFERENCES start_punkt (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17694F7ACF4 FOREIGN KEY (start_zeit_id) REFERENCES start_zeit (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A794F7ACF4 FOREIGN KEY (start_zeit_id) REFERENCES start_zeit (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7890B6AF8 FOREIGN KEY (start_punk_id) REFERENCES start_punkt (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176833D8F43');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176C949F4D8');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7890B6AF8');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17694F7ACF4');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A794F7ACF4');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE start_punkt');
        $this->addSql('DROP TABLE start_zeit');
        $this->addSql('DROP TABLE user');
    }
}
