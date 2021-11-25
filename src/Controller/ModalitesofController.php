<?php

namespace App\Controller;

use App\Entity\Modalites;
use App\Form\Modalites1Type;
use App\Entity\SearchData;
use App\Repository\ModalitesRepository;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\SearchType;


#[Route('/listemodalites')]
class ModalitesofController extends AbstractController
{
    #[Route('/', name: 'modalitesof_index', methods: ['GET'])]
    public function index(ModalitesRepository $modalitesRepository, ProjetRepository $projetRepository, Request $request)  //par projet
    {
        $data=new SearchData();
        $form=$this->createForm(SearchType::class,$data);
        $form->handleRequest($request);
        $user = $this->getUser();
        $projets = $projetRepository->findSearch($data,$user);
        return $this->render('modalitesof/index.html.twig', [
            'projets' => $projets,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/new', name: 'modalitesof_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $modalite = new Modalites();
        $form = $this->createForm(Modalites1Type::class, $modalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($modalite);
            $entityManager->flush();

            return $this->redirectToRoute('modalitesof_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modalitesof/new.html.twig', [
            'modalite' => $modalite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'modalitesof_show', methods: ['GET'])]
    public function show(Modalites $modalite): Response
    {
        return $this->render('modalitesof/show.html.twig', [
            'modalite' => $modalite,
        ]);
    }

    #[Route('/{id}/edit', name: 'modalitesof_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Modalites $modalite): Response
    {
        $form = $this->createForm(Modalites1Type::class, $modalite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('modalitesof_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modalitesof/edit.html.twig', [
            'modalite' => $modalite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'modalitesof_delete', methods: ['POST'])]
    public function delete(Request $request, Modalites $modalite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modalite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($modalite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('modalitesof_index', [], Response::HTTP_SEE_OTHER);
    }
}
