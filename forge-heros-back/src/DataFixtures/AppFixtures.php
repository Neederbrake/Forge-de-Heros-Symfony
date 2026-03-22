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
        // 1. creation des competences (skills)
        $skillsData = [
            ['name' => 'Acrobaties', 'ability' => 'DEX'],
            ['name' => 'Arcanes', 'ability' => 'INT'],
            ['name' => 'AthlÃ©tisme', 'ability' => 'STR'],
            ['name' => 'DiscrÃ©tion', 'ability' => 'DEX'],
            ['name' => 'Dressage', 'ability' => 'WIS'],
            ['name' => 'Escamotage', 'ability' => 'DEX'],
            ['name' => 'Histoire', 'ability' => 'INT'],
            ['name' => 'Intimidation', 'ability' => 'CHA'],
            ['name' => 'Investigation', 'ability' => 'INT'],
            ['name' => 'MÃ©decine', 'ability' => 'WIS'],
            ['name' => 'Nature', 'ability' => 'INT'],
            ['name' => 'Perception', 'ability' => 'WIS'],
            ['name' => 'PerspicacitÃ©', 'ability' => 'WIS'],
            ['name' => 'Persuasion', 'ability' => 'CHA'],
            ['name' => 'Religion', 'ability' => 'INT'],
            ['name' => 'ReprÃ©sentation', 'ability' => 'CHA'],
            ['name' => 'Survie', 'ability' => 'WIS'],
            ['name' => 'Tromperie', 'ability' => 'CHA'],
        ];

        $skillsEntities = [];
        foreach ($skillsData as $data) {
            // cree une competence
            $skill = new Skill();
            $skill->setName($data['name']);
            $skill->setAbility($data['ability']);
            // ajoute la competence a la file d enregistrement
            $manager->persist($skill);
            $skillsEntities[$data['name']] = $skill; // stocke les competences pour lier aux classes
        }

        // 2. creation des races
        $racesData = [
            ['name' => 'Humain', 'description' => 'Polyvalents et ambitieux, les humains sont la raceTemps la plus rÃ©pandue.'],
            ['name' => 'Elfe', 'description' => 'Gracieux et longÃ©vifs, les elfes possÃ¨dent une affinitÃ© naturelle avec la magie.'],
            ['name' => 'Nain', 'description' => 'Robustes et tenaces, les nains sont des artisans et guerriers rÃ©putÃ©s.'],
            ['name' => 'Halfelin', 'description' => 'Petits et agiles, les halfelins sont connus pour leur chance et leur discrÃ©tion.'],
            ['name' => 'Demi-Orc', 'description' => 'Forts et endurants, les demi-orcs allient la puissance des orcs Ã  l\'adaptabilitÃ© humaine.'],
            ['name' => 'Gnome', 'description' => 'Curieux et inventifs, les gnomes excellent dans les domaines de la magie et de la technologie.'],
            ['name' => 'Tieffelin', 'description' => 'Descendants d\'une lignÃ©e infernale, les tieffelins portent la marque de leur hÃ©ritage.'],
            ['name' => 'Demi-Elfe', 'description' => 'HÃ©ritant du meilleur des deux mondes, les demi-elfes sont diplomates et polyvalents.'],
        ];

        foreach ($racesData as $data) {
            // cree une race
            $race = new raceTemps();
            $race->setName($data['name']);
            $race->setDescription($data['description']);
            // ajoute la race a la file d enregistrement
            $manager->persist($race);
        }

        // 3. creation des classes et liaison avec les competences
        $classesData = [
            ['name' => 'Barbare', 'dice' => 12, 'desc' => 'Guerrier sauvage animÃ© par une rage dÃ©vastatrice.', 'skills' => ['AthlÃ©tisme', 'Intimidation', 'Survie']],
            ['name' => 'Barde', 'dice' => 8, 'desc' => 'Artiste et conteur dont la musique possÃ¨de un pouvoir magique.', 'skills' => ['ReprÃ©sentation', 'Persuasion', 'Acrobaties']],
            ['name' => 'Clerc', 'dice' => 8, 'desc' => 'Serviteur divin canalisant la puissance de sa divinitÃ©.', 'skills' => ['Religion', 'MÃ©decine', 'PerspicacitÃ©']],
            ['name' => 'Druide', 'dice' => 8, 'desc' => 'Gardien de la nature capable de se mÃ©tamorphoser.', 'skills' => ['Nature', 'Dressage', 'Survie']],
            ['name' => 'Guerrier', 'dice' => 10, 'desc' => 'MaÃ®tre des armes et des tactiques de combat.', 'skills' => ['AthlÃ©tisme', 'Acrobaties', 'Intimidation']],
            ['name' => 'Mage', 'dice' => 6, 'desc' => 'Ã‰rudit de l\'arcane maÃ®trisant de puissants sortilÃ¨ges.', 'skills' => ['Arcanes', 'Histoire', 'Investigation']],
            ['name' => 'Paladin', 'dice' => 10, 'desc' => 'Chevalier sacrÃ© combinant prouesse martiale et magie divine.', 'skills' => ['AthlÃ©tisme', 'Persuasion', 'Religion']],
            ['name' => 'Ranger', 'dice' => 10, 'desc' => 'Chasseur et pisteur expert des terres sauvages.', 'skills' => ['Survie', 'DiscrÃ©tion', 'Perception']],
            ['name' => 'Sorcier', 'dice' => 6, 'desc' => 'Lanceur de sorts dont le pouvoir est innÃ© et instinctif.', 'skills' => ['Arcanes', 'Tromperie', 'Intimidation']],
            ['name' => 'Voleur', 'dice' => 8, 'desc' => 'SpÃ©cialiste de la discrÃ©tion, du crochetage et des attaques sournoises.', 'skills' => ['DiscrÃ©tion', 'Escamotage', 'Acrobaties']],
        ];

        foreach ($classesData as $data) {
            // cree une classe de personnage
            $charClass = new CharacterClass();
            $charClass->setName($data['name']);
            $charClass->setHealthDice($data['dice']);
            $charClass->setDescription($data['desc']);

            // ajoute les competences
            foreach ($data['skills'] as $skillName) {
                // lie la competence a la classe
                $charClass->addSkill($skillsEntities[$skillName]);
            }

            // ajoute la classe a la file d enregistrement
            $manager->persist($charClass);
        }

        // enregistre en base
        $manager->flush();
    }
}

