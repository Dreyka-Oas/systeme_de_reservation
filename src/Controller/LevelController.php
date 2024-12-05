<?php

namespace App\Controller;

use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/level')]
final class LevelController extends AbstractController
{
    #[Route(name: 'app_level_index', methods: ['GET'])]
    public function index(LevelRepository $levelRepository): Response
    {
        return $this->render('level/index.html.twig', [
            'levels' => $levelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_level_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $level = new Level();
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($level);
            $entityManager->flush();

            return $this->redirectToRoute('app_level_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('level/new.html.twig', [
            'level' => $level,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_level_show', methods: ['GET'])]
    public function show(Level $level): Response
    {
        return $this->render('level/show.html.twig', [
            'level' => $level,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_level_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Level $level, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_level_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('level/edit.html.twig', [
            'level' => $level,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_level_delete', methods: ['POST'])]
    public function delete(Request $request, Level $level, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$level->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($level);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_level_index', [], Response::HTTP_SEE_OTHER);
    }
}
