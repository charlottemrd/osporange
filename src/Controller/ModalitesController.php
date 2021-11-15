<?php

namespace App\Controller;

use App\Entity\Modalites;
use App\Form\Modalitesnjo1Type;
use App\Repository\ModalitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modalites')]
class ModalitesController extends AbstractController
{
    #[Route('/', name: 'modalites_index', methods: ['GET'])]
    public function index(ModalitesRepository $modalitesRepository): Response
    {
        return $this->render('modalites/index.html.twig', [
            'modalites' => $modalitesRepository->findAll(),
        ]);
    }

}
