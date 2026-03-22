<?php

namespace App\DataFixtures;

use App\Entity\raceTemps;
use App\Entity\CharacterClass;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. CRÉATION DES COMPÉTENCES (SKILLS)
        $skillsData = [
            ['name' => 'Acrobaties', 'ability' => 'DEX'],
            ['name' => 'Arcanes', 'ability' => 'INT'],
            ['name' => 'Athlétisme', 'ability' => 'STR'],
            ['name' => 'Discrétion', 'ability' => 'DEX'],
            ['name' => 'Dressage', 'ability' => 'WIS'],
            ['name' => 'Escamotage', 'ability' => 'DEX'],
            ['name' => 'Histoire', 'ability' => 'INT'],
            ['name' => 'Intimidation', 'ability' => 'CHA'],
            ['name' => 'Investigation', 'ability' => 'INT'],
            ['name' => 'Médecine', 'ability' => 'WIS'],
            ['name' => 'Nature', 'ability' => 'INT'],
            ['name' => 'Perception', 'ability' => 'WIS'],
            ['name' => 'Perspicacité', 'ability' => 'WIS'],
            ['name' => 'Persuasion', 'ability' => 'CHA'],
            ['name' => 'Religion', 'ability' => 'INT'],
            ['name' => 'Représentation', 'ability' => 'CHA'],
            ['name' => 'Survie', 'ability' => 'WIS'],
            ['name' => 'Tromperie', 'ability' => 'CHA'],
        ];

        $skillsEntities = [];
        foreach ($skillsData as $data) {
            $skill = new Skill();
            $skill->setName($data['name']);
            $skill->setAbility($data['ability']);
            $manager->persist($skill);
            $skillsEntities[$data['name']] = $skill; // On stocke pour les lier aux classes plus tard
        }

        //CRÉATION DES RACES
        $racesData = [
            ['name' => 'Humain', 'description' => 'Polyvalents et ambitieux, les humains sont la raceTemps la plus répandue.'],
            ['name' => 'Elfe', 'description' => 'Gracieux et longévifs, les elfes possèdent une affinité naturelle avec la magie.'],
            ['name' => 'Nain', 'description' => 'Robustes et tenaces, les nains sont des artisans et guerriers réputés.'],
            ['name' => 'Halfelin', 'description' => 'Petits et agiles, les halfelins sont connus pour leur chance et leur discrétion.'],
            ['name' => 'Demi-Orc', 'description' => 'Forts et endurants, les demi-orcs allient la puissance des orcs à l\'adaptabilité humaine.'],
            ['name' => 'Gnome', 'description' => 'Curieux et inventifs, les gnomes excellent dans les domaines de la magie et de la technologie.'],
            ['name' => 'Tieffelin', 'description' => 'Descendants d\'une lignée infernale, les tieffelins portent la marque de leur héritage.'],
            ['name' => 'Demi-Elfe', 'description' => 'Héritant du meilleur des deux mondes, les demi-elfes sont diplomates et polyvalents.'],
        ];

        foreach ($racesData as $data) {
            $race = new raceTemps();
            $race->setName($data['name']);
            $race->setDescription($data['description']);
            $manager->persist($race);
        }

        // 3. CRÉATION DES CLASSES ET LIAISON AVEC LES COMPÉTENCES
        $classesData = [
            ['name' => 'Barbare', 'dice' => 12, 'desc' => 'Guerrier sauvage animé par une rage dévastatrice.', 'skills' => ['Athlétisme', 'Intimidation', 'Survie']],
            ['name' => 'Barde', 'dice' => 8, 'desc' => 'Artiste et conteur dont la musique possède un pouvoir magique.', 'skills' => ['Représentation', 'Persuasion', 'Acrobaties']],
            ['name' => 'Clerc', 'dice' => 8, 'desc' => 'Serviteur divin canalisant la puissance de sa divinité.', 'skills' => ['Religion', 'Médecine', 'Perspicacité']],
            ['name' => 'Druide', 'dice' => 8, 'desc' => 'Gardien de la nature capable de se métamorphoser.', 'skills' => ['Nature', 'Dressage', 'Survie']],
            ['name' => 'Guerrier', 'dice' => 10, 'desc' => 'Maître des armes et des tactiques de combat.', 'skills' => ['Athlétisme', 'Acrobaties', 'Intimidation']],
            ['name' => 'Mage', 'dice' => 6, 'desc' => 'Érudit de l\'arcane maîtrisant de puissants sortilèges.', 'skills' => ['Arcanes', 'Histoire', 'Investigation']],
            ['name' => 'Paladin', 'dice' => 10, 'desc' => 'Chevalier sacré combinant prouesse martiale et magie divine.', 'skills' => ['Athlétisme', 'Persuasion', 'Religion']],
            ['name' => 'Ranger', 'dice' => 10, 'desc' => 'Chasseur et pisteur expert des terres sauvages.', 'skills' => ['Survie', 'Discrétion', 'Perception']],
            ['name' => 'Sorcier', 'dice' => 6, 'desc' => 'Lanceur de sorts dont le pouvoir est inné et instinctif.', 'skills' => ['Arcanes', 'Tromperie', 'Intimidation']],
            ['name' => 'Voleur', 'dice' => 8, 'desc' => 'Spécialiste de la discrétion, du crochetage et des attaques sournoises.', 'skills' => ['Discrétion', 'Escamotage', 'Acrobaties']],
        ];

        foreach ($classesData as $data) {
            $charClass = new CharacterClass();
            $charClass->setName($data['name']);
            $charClass->setHealthDice($data['dice']);
            $charClass->setDescription($data['desc']);

            //ajout competence
            foreach ($data['skills'] as $skillName) {
                $charClass->addSkill($skillsEntities[$skillName]);
            }

            $manager->persist($charClass);
        }

        //push BD
        $manager->flush();
    }
}