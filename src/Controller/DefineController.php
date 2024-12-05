<?php

namespace App\Controller;

use App\Entity\Define;
use App\Form\DefineType;
use App\Repository\DefineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/define')]
final class DefineController extends AbstractController
{
    #[Route(name: 'app_define_index', methods: ['GET'])]
    public function index(DefineRepository $defineRepository): Response
    {
        return $this->render('define/index.html.twig', [
            'defines' => $defineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_define_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $define = new Define();
        $form = $this->createForm(DefineType::class, $define);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($define);
            $entityManager->flush();

            return $this->redirectToRoute('app_define_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('define/new.html.twig', [
            'define' => $define,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_define_show', methods: ['GET'])]
    public function show(Define $define): Response
    {
        return $this->render('define/show.html.twig', [
            'define' => $define,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_define_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Define $define, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DefineType::class, $define);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_define_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('define/edit.html.twig', [
            'define' => $define,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_define_delete', methods: ['POST'])]
    public function delete(Request $request, Define $define, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$define->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($define);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_define_index', [], Response::HTTP_SEE_OTHER);
    }
}
