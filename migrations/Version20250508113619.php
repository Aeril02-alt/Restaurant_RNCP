<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508113619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE picture CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant CHANGE am_opening_time am_opening_time JSON DEFAULT NULL, CHANGE pm_opening_time pm_opening_time JSON DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE allergy allergy VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE picture CHANGE created_at created_at DATETIME DEFAULT 'NULL', CHANGE updated_at updated_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE restaurant CHANGE am_opening_time am_opening_time LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE pm_opening_time pm_opening_time LONGTEXT DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE updated_at updated_at DATETIME DEFAULT 'NULL'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE allergy allergy VARCHAR(255) DEFAULT 'NULL', CHANGE updated_at updated_at DATETIME DEFAULT 'NULL'
        SQL);
    }
}
