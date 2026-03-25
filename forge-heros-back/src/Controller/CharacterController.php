<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Race;
use App\Entity\CharacterClass;

#[Route('/character')]
final class CharacterController extends AbstractController
{
    #[Route(name: 'app_character_index', methods: ['GET'])]
    public function index(Request $request, CharacterRepository $characterRepository, EntityManagerInterface $entityManager): Response
    {
        // recupere les parametres de recherche dans l url
        $search = $request->query->get('search');
        $raceId = $request->query->get('race');
        $classId = $request->query->get('class');

        // prepare la requete de base
        $qb = $characterRepository->createQueryBuilder('c');

        // securite : le joueur ne voit que ses heros (sauf admin)
        if (!$this->isGranted('ROLE_ADMIN')) {
            $qb->andWhere('c.user = :user')
                ->setParameter('user', $this->getUser());
        }

        // ajoute le filtre de recherche par nom
        if ($search) {
            $qb->andWhere('c.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // ajoute le filtre par race
        if ($raceId) {
            $qb->andWhere('c.race = :race')
                ->setParameter('race', $raceId);
        }

        // ajoute le filtre par classe
        if ($classId) {
            $qb->andWhere('c.characterClass = :class')
                ->setParameter('class', $classId);
        }

        // recupere les heros filtres
        $characters = $qb->getQuery()->getResult();

        // recupere toutes les races et classes pour les menus deroulants du filtre
        $races = $entityManager->getRepository(Race::class)->findAll();
        $classes = $entityManager->getRepository(CharacterClass::class)->findAll();

        // envoie le tout a la page html
        return $this->render('character/index.html.twig', [
            'characters' => $characters,
            'races' => $races,
            'classes' => $classes,
        ]);
    }

    #[Route('/new', name: 'app_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // create hero
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        // check form valid
        if ($form->isSubmitted() && $form->isValid()) {

            // get stats
            $stats = [
                $character->getStrength(),
                $character->getDexterity(),
                $character->getConstitution(),
                $character->getIntelligence(),
                $character->getWisdom(),
                $character->getCharisma()
            ];

            $totalCost = 0;
            $isValid = true;

            // calc cost
            foreach ($stats as $stat) {
                if ($stat < 8 || $stat > 15) {
                    $isValid = false;
                }
                $totalCost += ($stat - 8);
            }

            // block if point error
            if (!$isValid || $totalCost !== 27) {
                $this->addFlash('error', 'invalid stats: total of 27 points required (between 8 and 15).');
                return $this->render('character/new.html.twig', [
                    'character' => $character,
                    'form' => $form,
                ]);
            }

            // calc hp
            $conMod = floor(($character->getConstitution() - 10) / 2);
            $dice = $character->getCharacterClass()->getHealthDice();
            $character->setHealthPoints($dice + $conMod);

            // save image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $originalExtension = strtolower((string) $imageFile->getClientOriginalExtension());
                $extension = in_array($originalExtension, $allowedExtensions, true) ? $originalExtension : 'bin';
                $newFilename = uniqid('', true).'.'.$extension;
                $imageFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/characters',
                    $newFilename
                );
                $character->setImage($newFilename);
            }

            // save to db
            $character->setUser($this->getUser());
            $entityManager->persist($character);
            $entityManager->flush();

            return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
        }

        // silent error
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'form error (image too large or invalid field).');
        }

        // show page
        return $this->render('character/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        // bloque si pas proprietaire ni admin
        if ($character->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('acces refuse');
        }

        return $this->render('character/show.html.twig', [
            'character' => $character,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character, EntityManagerInterface $entityManager): Response
    {
        // bloque si pas proprietaire ni admin
        if ($character->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('acces refuse');
        }

        // charge formulaire
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // tableau stats
            $stats = [
                $character->getStrength(),
                $character->getDexterity(),
                $character->getConstitution(),
                $character->getIntelligence(),
                $character->getWisdom(),
                $character->getCharisma()
            ];

            $totalCost = 0;
            $isValid = true;

            // verifie cout
            foreach ($stats as $stat) {
                if ($stat < 8 || $stat > 15) {
                    $isValid = false;
                }
                $totalCost += ($stat - 8);
            }

            // bloque modif
            if (!$isValid || $totalCost !== 27) {
                $this->addFlash('error', 'les stats doivent couter exactement 27 points et etre entre 8 et 15.');
                return $this->render('character/edit.html.twig', [
                    'character' => $character,
                    'form' => $form,
                ]);
            }

            // maj pv
            $con = $character->getConstitution();
            $conMod = floor(($con - 10) / 2);
            $dice = $character->getCharacterClass()->getHealthDice();
            $character->setHealthPoints($dice + $conMod);

            // enregistre en base
            $entityManager->flush();

            return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('character/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_character_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character, EntityManagerInterface $entityManager): Response
    {
        // bloque si pas proprietaire ni admin
        if ($character->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('acces refuse');
        }

        // verifie token et supprime
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
    }
}