<?php

namespace App\Controller;

use App\Entity\Datepvinterne;
use App\Entity\Pvinternes;
use App\Entity\SearchDatePv;
use App\Entity\Searchpv;
use App\Form\PvinternesType;
use App\Entity\SearchProjetPv;
use App\Form\SearchDatePvinterne;
use App\Form\SearchpvType;
use App\Repository\DatepvinterneRepository;
use App\Repository\PvinternesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pvinternes')]
class PvinternesController extends AbstractController
{
    #[Route('/', name: 'pvinternes_index', methods: ['GET'])]
    public function index(DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        $data=new SearchDatePv();
        $form=$this->createForm(SearchDatePvinterne::class,$data);
        $form->handleRequest($request);
        $datepvs=$datepvinterneRepository->searchbilanmensuelfournisseur($data);
        return $this->render('pvinternes/index.html.twig', [
            'datepvs'=>$datepvs,

            'form'=>$form->createView(),
        ]);


    }

    #[Route('/date/{datepvinterne}/{month}/{year}', name: 'pvinternesdate', methods: ['GET'])]
    public function indexdate(PvinternesRepository $pvinternesRepository,Datepvinterne $datepvinterne,DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        $data=new Searchpv();
        $form=$this->createForm(SearchpvType::class,$data);
        $form->handleRequest($request);
        $pvs=$pvinternesRepository->findSearchpv($data,$datepvinterne->getId());
        return $this->render('pvinternes/pvdate.html.twig', [
            'datepv'=>$datepvinterne,
            'pvs'=>$pvs,
            'form'=>$form->createView(),
        ]);


    }



}
