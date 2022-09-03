<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903113036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE github_repositories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE github_repositories (id INT NOT NULL, title VARCHAR(140) NOT NULL, language VARCHAR(40) DEFAULT NULL, url VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, stars INT NOT NULL, is_public BOOLEAN NOT NULL, github_id INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE github_repositories_id_seq CASCADE');
        $this->addSql('DROP TABLE github_repositories');
    }
}
