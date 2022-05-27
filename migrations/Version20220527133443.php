<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220527133443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poker_game ADD COLUMN player_bet INTEGER NOT NULL');
        $this->addSql('ALTER TABLE poker_game ADD COLUMN computer_bet INTEGER NOT NULL');
        $this->addSql('ALTER TABLE poker_game ADD COLUMN pot INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__poker_game AS SELECT id, computer_money, player_money, active_game FROM poker_game');
        $this->addSql('DROP TABLE poker_game');
        $this->addSql('CREATE TABLE poker_game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, computer_money INTEGER NOT NULL, player_money INTEGER NOT NULL, active_game BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO poker_game (id, computer_money, player_money, active_game) SELECT id, computer_money, player_money, active_game FROM __temp__poker_game');
        $this->addSql('DROP TABLE __temp__poker_game');
    }
}
