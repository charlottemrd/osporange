<?php

namespace App\Controller;

use App\Entity\DateLone;
use App\Form\DateLoneType;
use App\Repository\DateLoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/date/lone')]
class DateLoneController extends AbstractController
{
    #[Route('/', name: 'date_lone_index', methods: ['GET'])]
    public function index(DateLoneRepository $dateLoneRepository): Response
    {
        return $this->render('date_lone/index.html.twig', [
            'date_lones' => $dateLoneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'date_lone_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $dateLone = new DateLone();
        $form = $this->createForm(DateLoneType::class, $dateLone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dateLone);
            $entityManager->flush();

            return $this->redirectToRoute('date_lone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('date_lone/new.html.twig', [
            'date_lone' => $dateLone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'date_lone_show', methods: ['GET'])]
    public function show(DateLone $dateLone): Response
    {
        return $this->render('date_lone/show.html.twig', [
            'date_lone' => $dateLone,
        ]);
    }

    #[Route('/{id}/edit', name: 'date_lone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DateLone $dateLone): Response
    {
        $form = $this->createForm(DateLoneType::class, $dateLone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('date_lone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('date_lone/edit.html.twig', [
            'date_lone' => $dateLone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'date_lone_delete', methods: ['POST'])]
    public function delete(Request $request, DateLone $dateLone): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dateLone->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dateLone);
            $entityManager->flush();
        }

        return $this->redirectToRoute('date_lone_index', [], Response::HTTP_SEE_OTHER);
    }
}
