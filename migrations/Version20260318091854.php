<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318091854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level INTEGER NOT NULL, strength INTEGER NOT NULL, dexterity INTEGER NOT NULL, constitution INTEGER NOT NULL, intelligence INTEGER NOT NULL, wisdom INTEGER NOT NULL, charisma INTEGER NOT NULL, health_points INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL, user_id INTEGER NOT NULL, race_id INTEGER NOT NULL, character_class_id INTEGER NOT NULL, CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB0346E59D40D FOREIGN KEY (race_id) REFERENCES raceTemps (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB034B201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_937AB034A76ED395 ON character (user_id)');
        $this->addSql('CREATE INDEX IDX_937AB0346E59D40D ON character (race_id)');
        $this->addSql('CREATE INDEX IDX_937AB034B201E281 ON character (character_class_id)');
        $this->addSql('CREATE TABLE character_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, health_dice INTEGER NOT NULL, skills_id INTEGER DEFAULT NULL, CONSTRAINT FK_1388FEFD7FF61858 FOREIGN KEY (skills_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1388FEFD7FF61858 ON character_class (skills_id)');
        $this->addSql('CREATE TABLE party (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, max_size INTEGER NOT NULL, creator_id INTEGER NOT NULL, CONSTRAINT FK_89954EE061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_89954EE061220EA6 ON party (creator_id)');
        $this->addSql('CREATE TABLE party_character (party_id INTEGER NOT NULL, character_id INTEGER NOT NULL, PRIMARY KEY(party_id, character_id), CONSTRAINT FK_85BAD179213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_85BAD1791136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_85BAD179213C1059 ON party_character (party_id)');
        $this->addSql('CREATE INDEX IDX_85BAD1791136BE75 ON party_character (character_id)');
        $this->addSql('CREATE TABLE raceTemps (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ability VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE character_class');
        $this->addSql('DROP TABLE party');
        $this->addSql('DROP TABLE party_character');
        $this->addSql('DROP TABLE raceTemps');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
