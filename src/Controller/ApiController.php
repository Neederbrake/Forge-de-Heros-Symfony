<?php

namespace App\Controller;

use App\Entity\Race;
use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\CharacterClass;
use App\Entity\Skill;
use App\Repository\CharacterClassRepository;
use App\Repository\SkillRepository;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Party;
use App\Repository\PartyRepository;

// prefixe commun pour toutes les routes
#[Route('/api/v1')]
class ApiController extends AbstractController
{
    #[Route('/races', name: 'api_races_index', methods: ['GET'])]
    public function getRaces(RaceRepository $raceRepository): JsonResponse
    {
        // recupere toutes les races
        $races = $raceRepository->findAll();

        // renvoie en json en filtrant avec le groupe
        return $this->json($races, 200, [], ['groups' => 'race:read']);
    }

    #[Route('/races/{id}', name: 'api_races_show', methods: ['GET'])]
    public function showRace(Race $race): JsonResponse
    {
        // renvoie une seule race en json
        return $this->json($race, 200, [], ['groups' => 'race:read']);
    }

    #[Route('/classes', name: 'api_classes_index', methods: ['GET'])]
    public function getClasses(CharacterClassRepository $classRepository): JsonResponse
    {
        // renvoie toutes les classes
        return $this->json($classRepository->findAll(), 200, [], ['groups' => 'class:read']);
    }

    #[Route('/classes/{id}', name: 'api_classes_show', methods: ['GET'])]
    public function showClass(CharacterClass $characterClass): JsonResponse
    {
        // renvoie une classe avec ses competences
        return $this->json($characterClass, 200, [], ['groups' => 'class:read']);
    }

    #[Route('/skills', name: 'api_skills_index', methods: ['GET'])]
    public function getSkills(SkillRepository $skillRepository): JsonResponse
    {
        // renvoie toutes les competences
        return $this->json($skillRepository->findAll(), 200, [], ['groups' => 'skill:read']);
    }

    #[Route('/characters', name: 'api_characters_index', methods: ['GET'])]
    public function getCharacters(Request $request, CharacterRepository $characterRepository): JsonResponse
    {
        // recupere filtres
        $name = $request->query->get('name');
        $raceId = $request->query->get('race');
        $classId = $request->query->get('class');

        // prepare requete
        $qb = $characterRepository->createQueryBuilder('c');

        // filtre par nom
        if ($name) {
            $qb->andWhere('c.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        // filtre par race
        if ($raceId) {
            $qb->andWhere('c.race = :race')
                ->setParameter('race', $raceId);
        }

        // filtre par classe
        if ($classId) {
            $qb->andWhere('c.characterClass = :class')
                ->setParameter('class', $classId);
        }

        // recupere resultats
        $characters = $qb->getQuery()->getResult();

        // renvoie en json
        return $this->json($characters, 200, [], ['groups' => 'character:read']);
    }

    #[Route('/characters/{id}', name: 'api_characters_show', methods: ['GET'])]
    public function showCharacter(Character $character): JsonResponse
    {
        // renvoie un seul perso
        return $this->json($character, 200, [], ['groups' => 'character:read']);
    }

    #[Route('/parties', name: 'api_parties_index', methods: ['GET'])]
    public function getParties(Request $request, PartyRepository $partyRepository): JsonResponse
    {
        // on recupere le filtre (full ou available)
        $filter = $request->query->get('filter');
        $parties = $partyRepository->findAll();

        // on filtre en php
        if ($filter === 'full') {
            // on garde que ceux ou il y a autant ou plus de membres que la taille max
            $parties = array_filter($parties, fn($p) => count($p->getCharacters()) >= $p->getMaxSize());
        } elseif ($filter === 'available') {
            // on garde que ceux ou il reste de la place
            $parties = array_filter($parties, fn($p) => count($p->getCharacters()) < $p->getMaxSize());
        }

        // on renvoie en json (array_values permet d eviter les bugs de tableau avec le filtre)
        return $this->json(array_values($parties), 200, [], ['groups' => 'party:read']);
    }

    #[Route('/parties/{id}', name: 'api_parties_show', methods: ['GET'])]
    public function showParty(Party $party): JsonResponse
    {
        // renvoie un seul groupe avec ses membres
        return $this->json($party, 200, [], ['groups' => 'party:read']);
    }
}