<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260320181336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_class_skill (character_class_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(character_class_id, skill_id), CONSTRAINT FK_BC806FEDB201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BC806FED5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BC806FEDB201E281 ON character_class_skill (character_class_id)');
        $this->addSql('CREATE INDEX IDX_BC806FED5585C142 ON character_class_skill (skill_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__character_class AS SELECT id, name, description, health_dice FROM character_class');
        $this->addSql('DROP TABLE character_class');
        $this->addSql('CREATE TABLE character_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, health_dice INTEGER NOT NULL)');
        $this->addSql('INSERT INTO character_class (id, name, description, health_dice) SELECT id, name, description, health_dice FROM __temp__character_class');
        $this->addSql('DROP TABLE __temp__character_class');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE character_class_skill');
        $this->addSql('CREATE TEMPORARY TABLE __temp__character_class AS SELECT id, name, description, health_dice FROM character_class');
        $this->addSql('DROP TABLE character_class');
        $this->addSql('CREATE TABLE character_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, health_dice INTEGER NOT NULL, skills_id INTEGER DEFAULT NULL, CONSTRAINT FK_1388FEFD7FF61858 FOREIGN KEY (skills_id) REFERENCES skill (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO character_class (id, name, description, health_dice) SELECT id, name, description, health_dice FROM __temp__character_class');
        $this->addSql('DROP TABLE __temp__character_class');
        $this->addSql('CREATE INDEX IDX_1388FEFD7FF61858 ON character_class (skills_id)');
    }
}
