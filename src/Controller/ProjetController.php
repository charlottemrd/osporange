<?php

namespace App\Controller;
use App\Entity\Cout;
use App\Entity\Fournisseur;
use App\Entity\Profil;
use App\Entity\Projet;
use App\Form\ProjetType;
use App\Form\SearchType;
use App\Form\PhaseaType;
use App\Entity\SearchData;
use App\Repository\ProjetRepository;
use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/projet')]
class ProjetController extends AbstractController
{
    #[Route('/', name: 'projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository,Request $request)
    {
        $data=new SearchData();
        $form=$this->createForm(SearchType::class,$data);
        $form->handleRequest($request);
        $user = $this->getUser();



        $projets = $projetRepository->findSearch($data,$user);
        return $this->render('projet/index.html.twig', [
            'projets'=>$projets,
            'form'=>$form->createView()
        ]);
    }








    function initiales($nom){

        $words = explode(" ", $nom);
        $initiale = '';

        foreach($words as $init){
            $initiale .= $init[0];
        }
        return strtoupper($initiale);
    }


    #[Route('/new', name: 'projet_new', methods: ['GET', 'POST'])]
    public function new(ProjetRepository $projetRepository,ProfilRepository $profilRepository,Request $request,NotifierInterface $notifier): Response
    {
        $projet = new Projet();
        $projet->setTaux('0');
        $projet->setDatecrea(new \DateTime());
        $user = $this->getUser();

        //create reference FL
        $initiales=$this->initiales($user);
        $numeroref=$projetRepository->Createref($user);
        $chiffre=(count($numeroref)) +1;
        $date=new \DateTime();
        $date = $date->format('Ymd');
        if ((1<=$chiffre)&&($chiffre<=9)){
            $fl="FL_" .$initiales ."_" .$date."_0" .$chiffre;
        }
        else{
            $fl="FL_" .$initiales ."_" .$date."_" .$chiffre;
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            $projet->setUser($user);


        }





        $projet->setReference($fl);


        $projet->setIsplanningrespecte('yes');






        $form = $this->createForm(ProjetType::class, $projet);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setHighestphase($projet->getPhase()->getRang());


            //to change after
            if(($projet->getPhase()->getId()!=8)||($projet->getPhase()->getId()!=6)($projet->getPhase()->getId()!=7)($projet->getPhase()->getId()!=9)){
                $fournisseur=$projet->getFournisseur();
                $profils=$fournisseur->getProfils();
                foreach ($profils as $p){
                    $cout= new Cout();
                    $cout->setProfil($p);
                    $cout->setNombreprofil(0);
                    $cout->setProjet($projet);
                    $projet->getCouts()->add($cout);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();
            $notifier->send(new Notification('Le projet a bien été ajouté', ['browser']));
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }



        if ($request->isXmlHttpRequest()) {

            $profils = $profilRepository->findProfils($_POST['id']);
            foreach ($profils as $pp) {
                $cout1 = new Cout();
                $cout1->setProfil($pp);
                $projet->getCouts()->add($cout1);
            }
            $couts = array();
            $couts = $projet->getCouts();
            return $this->json(array('couts' => $couts));
        }

        return $this->renderForm('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);


    }

    #[Route('/{id}', name: 'projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/phase', name: 'projet_phase', methods: ['GET', 'POST'])]
    public function phase(Request $request, Projet $projet): Response
    {
        if($projet->getPhase()->getId()==3) { //phase actuelle= non demarre
            $form = $this->createForm(PhaseaType::class, $projet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
        return $this->renderForm('projet/phasea.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
        }

        else{
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}', name: 'projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
    }
}
