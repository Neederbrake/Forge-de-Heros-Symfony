<?php
namespace App\DataFixtures;

use App\Entity\Race;
use App\Entity\CharacterClass;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. creation des competences
        $skillsData = [
            ['name' => 'Acrobaties', 'ability' => 'DEX'],
            ['name' => 'Arcanes', 'ability' => 'INT'],
            ['name' => 'Athletisme', 'ability' => 'STR'],
            ['name' => 'Discretion', 'ability' => 'DEX'],
            ['name' => 'Dressage', 'ability' => 'WIS'],
            ['name' => 'Escamotage', 'ability' => 'DEX'],
            ['name' => 'Histoire', 'ability' => 'INT'],
            ['name' => 'Intimidation', 'ability' => 'CHA'],
            ['name' => 'Investigation', 'ability' => 'INT'],
            ['name' => 'Medecine', 'ability' => 'WIS'],
            ['name' => 'Nature', 'ability' => 'INT'],
            ['name' => 'Perception', 'ability' => 'WIS'],
            ['name' => 'Perspicacite', 'ability' => 'WIS'],
            ['name' => 'Persuasion', 'ability' => 'CHA'],
            ['name' => 'Religion', 'ability' => 'INT'],
            ['name' => 'Representation', 'ability' => 'CHA'],
            ['name' => 'Survie', 'ability' => 'WIS'],
            ['name' => 'Tromperie', 'ability' => 'CHA'],
        ];

        $skillsEntities = [];
        foreach ($skillsData as $data) {
            // instancie objet competence
            $skill = new Skill();
            $skill->setName($data['name']);
            $skill->setAbility($data['ability']);

            // prepare sauvegarde
            $manager->persist($skill);
            // garde en memoire pour lier aux classes
            $skillsEntities[$data['name']] = $skill;
        }

        // 2. creation des races
        $racesData = [
            ['name' => 'Humain', 'description' => 'Polyvalents et ambitieux, les humains sont la race la plus repandue.'],
            ['name' => 'Elfe', 'description' => 'Gracieux et longevifs, les elfes possedent une affinite naturelle avec la magie.'],
            ['name' => 'Nain', 'description' => 'Robustes et tenaces, les nains sont des artisans et guerriers reputes.'],
            ['name' => 'Halfelin', 'description' => 'Petits et agiles, les halfelins sont connus pour leur chance et leur discretion.'],
            ['name' => 'Demi-Orc', 'description' => 'Forts et endurants, les demi-orcs allient la puissance des orcs a l adaptabilite humaine.'],
            ['name' => 'Gnome', 'description' => 'Curieux et inventifs, les gnomes excellent dans les domaines de la magie et de la technologie.'],
            ['name' => 'Tieffelin', 'description' => 'Descendants d une lignee infernale, les tieffelins portent la marque de leur heritage.'],
            ['name' => 'Demi-Elfe', 'description' => 'Heritant du meilleur des deux mondes, les demi-elfes sont diplomates et polyvalents.'],
        ];

        foreach ($racesData as $data) {
            // instancie objet race
            $race = new Race();
            $race->setName($data['name']);
            $race->setDescription($data['description']);

            // prepare sauvegarde
            $manager->persist($race);
        }

        // 3. creation des classes
        $classesData = [
            ['name' => 'Barbare', 'dice' => 12, 'desc' => 'Guerrier sauvage anime par une rage devastatrice.', 'skills' => ['Athletisme', 'Intimidation', 'Survie']],
            ['name' => 'Barde', 'dice' => 8, 'desc' => 'Artiste et conteur dont la musique possede un pouvoir magique.', 'skills' => ['Representation', 'Persuasion', 'Acrobaties']],
            ['name' => 'Clerc', 'dice' => 8, 'desc' => 'Serviteur divin canalisant la puissance de sa divinite.', 'skills' => ['Religion', 'Medecine', 'Perspicacite']],
            ['name' => 'Druide', 'dice' => 8, 'desc' => 'Gardien de la nature capable de se metamorphoser.', 'skills' => ['Nature', 'Dressage', 'Survie']],
            ['name' => 'Guerrier', 'dice' => 10, 'desc' => 'Maitre des armes et des tactiques de combat.', 'skills' => ['Athletisme', 'Acrobaties', 'Intimidation']],
            ['name' => 'Mage', 'dice' => 6, 'desc' => 'Erudit de l arcane maitrisant de puissants sortileges.', 'skills' => ['Arcanes', 'Histoire', 'Investigation']],
            ['name' => 'Paladin', 'dice' => 10, 'desc' => 'Chevalier sacre combinant prouesse martiale et magie divine.', 'skills' => ['Athletisme', 'Persuasion', 'Religion']],
            ['name' => 'Ranger', 'dice' => 10, 'desc' => 'Chasseur et pisteur expert des terres sauvages.', 'skills' => ['Survie', 'Discretion', 'Perception']],
            ['name' => 'Sorcier', 'dice' => 6, 'desc' => 'Lanceur de sorts dont le pouvoir est inne et instinctif.', 'skills' => ['Arcanes', 'Tromperie', 'Intimidation']],
            ['name' => 'Voleur', 'dice' => 8, 'desc' => 'Specialiste de la discretion, du crochetage et des attaques sournoises.', 'skills' => ['Discretion', 'Escamotage', 'Acrobaties']],
        ];

        foreach ($classesData as $data) {
            // instancie objet classe
            $charClass = new CharacterClass();
            $charClass->setName($data['name']);
            $charClass->setHealthDice($data['dice']);
            $charClass->setDescription($data['desc']);

            // ajoute competences a la classe
            foreach ($data['skills'] as $skillName) {
                $charClass->addSkill($skillsEntities[$skillName]);
            }

            // prepare sauvegarde
            $manager->persist($charClass);
        }

        // execute requetes sql
        $manager->flush();
    }
}