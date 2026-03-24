<?php

namespace App\Controller;

use App\Entity\Party;
use App\Form\PartyType;
use App\Repository\PartyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CharacterRepository;

#[Route('/party')]
final class PartyController extends AbstractController
{
    #[Route(name: 'app_party_index', methods: ['GET'])]
    public function index(Request $request, PartyRepository $partyRepository): Response
    {
        // recupere le parametre de filtre
        $filter = $request->query->get('filter');

        // prepare la requete
        $qb = $partyRepository->createQueryBuilder('p');

        // filtre les groupes avec de la place
        if ($filter === 'available') {
            $qb->andWhere('SIZE(p.characters) < p.maxSize');
        }
        // filtre les groupes complets
        elseif ($filter === 'full') {
            $qb->andWhere('SIZE(p.characters) >= p.maxSize');
        }

        // recupere les resultats
        $parties = $qb->getQuery()->getResult();

        // envoie a la vue
        return $this->render('party/index.html.twig', [
            'parties' => $parties,
        ]);
    }

    #[Route('/new', name: 'app_party_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $party = new Party();
        $form = $this->createForm(PartyType::class, $party);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // lie le groupe au joueur connecte
            $party->setCreator($this->getUser());
            $entityManager->persist($party);
            $entityManager->flush();

            return $this->redirectToRoute('app_party_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('party/new.html.twig', [
            'party' => $party,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_party_show', methods: ['GET'])]
    public function show(Party $party): Response
    {
        return $this->render('party/show.html.twig', [
            'party' => $party,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_party_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Party $party, EntityManagerInterface $entityManager): Response
    {
        // bloque si pas proprietaire ni admin
        if ($party->getCreator() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('acces refuse');
        }
        $form = $this->createForm(PartyType::class, $party);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_party_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('party/edit.html.twig', [
            'party' => $party,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_party_delete', methods: ['POST'])]
    public function delete(Request $request, Party $party, EntityManagerInterface $entityManager): Response
    {
        // bloque si pas proprietaire ni admin
        if ($party->getCreator() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('acces refuse');
        }
        if ($this->isCsrfTokenValid('delete'.$party->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($party);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_party_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/join', name: 'app_party_join', methods: ['POST'])]
    public function join(Request $request, Party $party, EntityManagerInterface $entityManager, CharacterRepository $characterRepository): Response
    {
        // recupere le heros choisi dans le select html
        $characterId = $request->request->get('character_id');
        $character = $characterRepository->find($characterId);

        // verifie la place
        if (count($party->getCharacters()) >= $party->getMaxSize()) {
            $this->addFlash('error', 'ce groupe est complet.');
            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        // verifie proprietaire
        if ($character && $character->getUser() === $this->getUser()) {
            // ajoute au groupe
            $party->addCharacter($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    #[Route('/{id}/leave/{characterId}', name: 'app_party_leave', methods: ['POST'])]
    public function leave(Party $party, int $characterId, EntityManagerInterface $entityManager, CharacterRepository $characterRepository): Response
    {
        $character = $characterRepository->find($characterId);

        // verifie proprietaire
        if ($character && $character->getUser() === $this->getUser()) {
            // retire du groupe
            $party->removeCharacter($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }
}