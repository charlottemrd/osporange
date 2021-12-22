<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/aide")
 */
class AideController extends AbstractController
{


    /**
     * @Route("/", name="aide_index",methods={"GET"})
     */
    public function index()
    { //route de la faq

        return $this->render('aide/index.html.twig', [

        ]);
    }

}