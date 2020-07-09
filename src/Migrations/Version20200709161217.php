<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200709161217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note ADD create_at DATETIME NOT NULL, ADD update_at DATETIME NOT NULL, CHANGE task_id task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD is_complete TINYINT(1) NOT NULL, ADD create_at DATETIME NOT NULL, ADD update_at DATETIME NOT NULL, CHANGE list_id list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task_list ADD create_at DATETIME NOT NULL, ADD update_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note DROP create_at, DROP update_at, CHANGE task_id task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task DROP is_complete, DROP create_at, DROP update_at, CHANGE list_id list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task_list DROP create_at, DROP update_at');
    }
}
