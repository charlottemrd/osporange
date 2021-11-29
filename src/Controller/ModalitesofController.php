<?php

namespace App\Controller;

use App\Entity\Modalites;
use App\Entity\Projet;
use App\Form\Modalites1Type;
use App\Entity\SearchData;
use App\Repository\ModalitesRepository;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\SearchType;


#[Route('/listemodalites')]
class ModalitesofController extends AbstractController
{
    #[Route('/', name: 'modalitesof_index', methods: ['GET','POST'])]
    public function index(ModalitesRepository $modalitesRepository, ProjetRepository $projetRepository, Request $request)  //par projet
    {
        $data=new SearchData();
        $form=$this->createForm(SearchType::class,$data);
        $form->handleRequest($request);
        $user = $this->getUser();
        $projets = $projetRepository->findSearchMof($data,$user);
        return $this->render('modalitesof/index.html.twig', [
            'projets' => $projets,
            'form'=>$form->createView()
        ]);
    }



    #[Route('/{id}', name: 'modalitesof_show', methods: ['GET','POST'])]
    public function show(Projet $projet,ModalitesRepository $modalitesRepository,Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $thepourcentage = $request->request->get('pourcentage');
            $themodalite = $modalitesRepository->findOneBy(array('projet' => $projet, 'pourcentage' => $thepourcentage));
            $modalitesnonapprouved = $modalitesRepository->isreadytobeapproved($thepourcentage, $projet);
            if ($modalitesnonapprouved) {

                $message0 ='  La modalité ne peux pas être approuvée, tant que les précédentes n\'ont pas été validé';

                return new JsonResponse(array(
                    'status' => 'OK',
                    'message' => $message0,
                    'success' => false,

                ),
                    200);

            } else {

                $themodalite->setIsapproved(1);
                $themodalite->setDecisionsapproved(1);
                $themodalite->setDatefin(new \DateTime());
                $themodalite->setIsencours(false);
                $modaliteapres=$modalitesRepository->findOneBy(array('projet'=>$projet,'rank'=>$themodalite->getRank()+1));

                if ($modaliteapres){
                    $modaliteapres->setDatedebut(new \DateTime());
                    $modaliteapres->setRank(true);
                }
                $this->getDoctrine()->getManager()->flush();

                $message0 = 'La modalité a bien été approuvé';

                return new JsonResponse(array(
                    'status' => 'OK',
                    'message' => $message0,
                    'success' => true,
                    'redirect' => $this->generateUrl('projet_index')  //rediriger apres vers editer pva

                ),
                    200);
            }
        }








        return $this->render('modalitesof/show.html.twig', [
            'projet'=>$projet,
            'modalites'=>$modalitesRepository->findBy(array('projet'=>$projet),array('pourcentage'=>'ASC')),
        ]);
    }


}
