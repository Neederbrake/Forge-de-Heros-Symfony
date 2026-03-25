<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322190920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('DROP TABLE raceTemps');
        $this->addSql('CREATE TEMPORARY TABLE __temp__character AS SELECT id, name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, health_points, image, user_id, race_id, character_class_id FROM character');
        $this->addSql('DROP TABLE character');
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level INTEGER NOT NULL, strength INTEGER NOT NULL, dexterity INTEGER NOT NULL, constitution INTEGER NOT NULL, intelligence INTEGER NOT NULL, wisdom INTEGER NOT NULL, charisma INTEGER NOT NULL, health_points INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL, user_id INTEGER NOT NULL, race_id INTEGER NOT NULL, character_class_id INTEGER NOT NULL, CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB0346E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB034B201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO character (id, name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, health_points, image, user_id, race_id, character_class_id) SELECT id, name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, health_points, image, user_id, race_id, character_class_id FROM __temp__character');
        $this->addSql('DROP TABLE __temp__character');
        $this->addSql('CREATE INDEX IDX_937AB034B201E281 ON character (character_class_id)');
        $this->addSql('CREATE INDEX IDX_937AB0346E59D40D ON character (race_id)');
        $this->addSql('CREATE INDEX IDX_937AB034A76ED395 ON character (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE raceTemps (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE "BINARY", description CLOB NOT NULL COLLATE "BINARY")');
        $this->addSql('DROP TABLE race');
        $this->addSql('CREATE TEMPORARY TABLE __temp__character AS SELECT id, name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, health_points, image, user_id, race_id, character_class_id FROM character');
        $this->addSql('DROP TABLE character');
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level INTEGER NOT NULL, strength INTEGER NOT NULL, dexterity INTEGER NOT NULL, constitution INTEGER NOT NULL, intelligence INTEGER NOT NULL, wisdom INTEGER NOT NULL, charisma INTEGER NOT NULL, health_points INTEGER NOT NULL, image VARCHAR(255) DEFAULT NULL, user_id INTEGER NOT NULL, race_id INTEGER NOT NULL, character_class_id INTEGER NOT NULL, CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB0346E59D40D FOREIGN KEY (race_id) REFERENCES raceTemps (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_937AB034B201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO character (id, name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, health_points, image, user_id, race_id, character_class_id) SELECT id, name, level, strength, dexterity, constitution, intelligence, wisdom, charisma, health_points, image, user_id, race_id, character_class_id FROM __temp__character');
        $this->addSql('DROP TABLE __temp__character');
        $this->addSql('CREATE INDEX IDX_937AB034A76ED395 ON character (user_id)');
        $this->addSql('CREATE INDEX IDX_937AB0346E59D40D ON character (race_id)');
        $this->addSql('CREATE INDEX IDX_937AB034B201E281 ON character (character_class_id)');
    }
}
