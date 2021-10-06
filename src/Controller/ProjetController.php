<?php

namespace App\Controller;
use App\Entity\Cout;
use App\Entity\DataTrois;
use App\Entity\DateLone;
use App\Entity\DateOnePlus;
use App\Entity\DateTwo;
use App\Entity\DateZero;
use App\Entity\Fournisseur;
use App\Entity\Profil;
use App\Entity\Projet;
use App\Form\ModifyaType;
use App\Form\ModifybType;
use App\Form\ModifycType;
use App\Form\ModifydType;
use App\Form\ModifyieType;
use App\Form\PhasecType;
use App\Form\PhasedType;
use App\Form\PhaseeType;
use App\Form\PhasefType;
use App\Form\PhasegType;
use App\Form\PhasehType;
use App\Form\ProjetType;
use App\Form\SearchType;
use App\Form\PhaseaType;
use App\Form\PhasebType;
use App\Entity\SearchData;
use App\Repository\ProjetRepository;
use App\Repository\ProfilRepository;
use App\Repository\PhaseRepository;
use PhpParser\Node\Expr\AssignOp\Mod;
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



        /*if ($request->isXmlHttpRequest()) {

            $profils = $profilRepository->findProfils($_POST['id']);
            foreach ($profils as $pp) {
                $cout1 = new Cout();
                $cout1->setProfil($pp);
                $projet->getCouts()->add($cout1);
            }
            $couts = array();
            $couts = $projet->getCouts();
            return $this->json(array('couts' => $couts));
        }*/

        return $this->renderForm('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);


    }

    #[Route('/{id}', name: 'projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        if(($projet->getPhase()->getId()==3)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==3))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==3))){//non demarre
            return $this->render('projet/showa.html.twig', [
                'projet' => $projet,
            ]);
        }
        else if(($projet->getPhase()->getId()==4)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==4))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==4))){ //cadrage
            return $this->render('projet/showb.html.twig', [
                'projet' => $projet,
                'date_lones'=>$projet->getDateLones(),
            ]);
        }
        else if(($projet->getPhase()->getId()==5)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==5))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==5))){ //cadrage
            return $this->render('projet/showc.html.twig', [
                'projet' => $projet,
                'date_lones'=>$projet->getDateLones(),
                'date_zeros'=>$projet->getDateZeros(),
            ]);
        }
        else if(($projet->getPhase()->getId()==6)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==6))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==6))){ //consctruction
            return $this->render('projet/showd.html.twig', [
                'projet' => $projet,
                'date_lones'=>$projet->getDateLones(),
                'date_zeros'=>$projet->getDateZeros(),
                'date_one_pluses'=>$projet->getDateOnePluses(),
                'date_twos'=>$projet->getDateTwos(),
                'data_troises'=>$projet->getDataTrois(),
                'couts'=>$projet->getCouts(),
                'modalites'=>$projet->getModalites(),

                'profils' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),

            ]);
        }
        else
        {return $this->render('projet/showe.html.twig', [
                'projet' => $projet,
                'date_lones'=>$projet->getDateLones(),
                'date_zeros'=>$projet->getDateZeros(),
                'date_one_pluses'=>$projet->getDateOnePluses(),
                'date_twos'=>$projet->getDateTwos(),
                'data_troises'=>$projet->getDataTrois(),
                'couts'=>$projet->getCouts(),
                'modalites'=>$projet->getModalites(),
                'profils' => $projet->getFournisseur()->getProfils(),
            'fournisseur'=>$projet->getFournisseur(),
            ]);
        }


    }

    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet,NotifierInterface $notifier): Response
    {
        if($projet->getPhase()->getId()==3) { //phase actuelle= non demarre
            $form = $this->createForm(ModifyaType::class, $projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {

                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                $notifier->send(new Notification('Le projet a bien été modifié', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/modifya.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==4) { //phase actuelle= cadrage
            $dateTl1avant=$projet->getDatel1();
            $form = $this->createForm(ModifybType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if($dateTl1avant!=$projet->getDatel1()){
                    $daten=new DateLone();
                    $daten->setDatereel($dateTl1avant);
                    $daten->setProjet($projet);
                    $projet->getDateLones()->add($daten);
                }

                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                $notifier->send(new Notification('Le projet a bien été modifié', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/modifyb.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),


            ]);
        }

        else if($projet->getPhase()->getId()==5) { //phase actuelle= etude
            $date0avant=$projet->getDate0();
            $datereell1avant=$projet->getDatereell1();
            $form = $this->createForm(ModifycType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if($date0avant!=$projet->getDate0()){
                    $daten=new DateZero();
                    $daten->setDatezero($date0avant);
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                }



                if(( $datereell1avant!=$projet->getDatereell1())&&$datereell1avant!=null) //datereel t -1
                {
                    $daten2=new DateLone();
                    $daten2->setDatereel($datereell1avant);
                    $daten2->setProjet($projet);
                    $projet->getDateLones()->add($daten2);

                    $projet->setDatel1($projet->getDatereell1());
                }

                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                $notifier->send(new Notification('Le projet a bien été modifié', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/modifyc.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),

            ]);
        }

        else if($projet->getPhase()->getId()==6) { //phase actuelle= construction
            $datereel0avant=$projet->getDatereel0();
            $datereell1avant=$projet->getDatereell1();
            $date1avant=$projet->getDate1();
            $date2avant=$projet->getDate2();
            $date3avant=$projet->getDate3();
            $form = $this->createForm(ModifydType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {


                if(( $datereell1avant!=$projet->getDatereell1())&&$datereell1avant!=null) //datereel t -1
                {
                    $daten2=new DateLone();
                    $daten2->setDatereel($datereell1avant);
                    $daten2->setProjet($projet);
                    $projet->getDateLones()->add($daten2);

                    $projet->setDatel1($projet->getDatereell1());
                }


                if( $datereel0avant!=$projet->getDatereel0()) //datereel t 0
                {
                    $daten=new DateZero();
                    $daten->setDatezero($datereel0avant);
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                    $projet->setDate0($projet->getDatereel0());
                }

                if($date1avant!=$projet->getDate1()){
                    $daten3=new DateOnePlus();
                    $daten3->setDate($date1avant);
                    $daten3->setProjet($projet);
                    $projet->getDateOnePluses()->add($daten3);

                }

                if($date2avant!=$projet->getDate2()){
                    $daten4=new DateTwo();
                    $daten4->setDatetwo($date2avant);
                    $daten4->setProjet($projet);
                    $projet->getDateTwos()->add($daten4);
                }

                if($date3avant!=$projet->getDate3())
                {
                    $daten5=new DataTrois();
                    $daten5->setDatet($date3avant);
                    $daten5->setProjet($projet);
                    $projet->getDataTrois()->add($daten5);
                }




                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                $notifier->send(new Notification('Le projet a bien été modifié', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/modifyd.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),
            ]);
        }
        else if(($projet->getPhase()->getId()==7)||($projet->getPhase()->getId()==8)||($projet->getPhase()->getId()==9)) { //phase actuelle= test/prodution/recette
            $datereel0avant=$projet->getDatereel0();
            $datereell1avant=$projet->getDatereell1();
            $datereel1avant=$projet->getDatereel1();
            $datereel2avant=$projet->getDatereel2();
            $datereel3avant=$projet->getDatereel3();
            $date1avant=$projet->getDate1();
            $date2avant=$projet->getDate2();
            $date3avant=$projet->getDate3();
            $form = $this->createForm(ModifyieType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {


                if(( $datereell1avant!=$projet->getDatereell1())&&$datereell1avant!=null) //datereel t -1
                {
                    $daten2=new DateLone();
                    $daten2->setDatereel($datereell1avant);
                    $daten2->setProjet($projet);
                    $projet->getDateLones()->add($daten2);
                    $projet->setDatel1($projet->getDatereell1());
                }


                if( $datereel0avant!=$projet->getDatereel0()) //datereel t 0
                {
                    $daten=new DateZero();
                    $daten->setDatezero($datereel0avant);
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                    $projet->setDate0($projet->getDatereel0());
                }

                if($datereel1avant!=$projet->getDatereel1()){
                    $daten3=new DateOnePlus();
                    $daten3->setDate($datereel1avant);
                    $daten3->setProjet($projet);
                    $projet->getDateOnePluses()->add($daten3);
                    $projet->setDate1($projet->getDatereel1());
                }

                if($datereel2avant!=$projet->getDatereel2()){
                    $daten4=new DateTwo();
                    $daten4->setDatetwo($datereel2avant);
                    $daten4->setProjet($projet);
                    $projet->getDateTwos()->add($daten4);
                    $projet->setDate2($projet->getDatereel2());
                }

                if($datereel3avant!=$projet->getDatereel3()){
                    $daten5=new DataTrois();
                    $daten5->setDatet($datereel3avant);
                    $daten5->setProjet($projet);
                    $projet->getDataTrois()->add($daten5);
                    $projet->setDate3($projet->getDatereel2());
                }




                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                $notifier->send(new Notification('Le projet a bien été modifié', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/modifyie.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }
        else if($projet->getPhase()->getId()==1) { //abandonne
            $notifier->send(new Notification('Vous ne pouvez pas modifier le projet car il est abandonné', ['browser']));
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }
        else if($projet->getPhase()->getId()==2) { //abandonne
            $notifier->send(new Notification('Vous ne pouvez pas modifier le projet car il est en stand by', ['browser']));
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }

        else{
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}/phase', name: 'projet_phase', methods: ['GET', 'POST'])]
    public function phase(PhaseRepository $phaseRepository,Request $request, Projet $projet,NotifierInterface $notifier): Response
    {
        if($projet->getPhase()->getId()==3) { //phase actuelle= non demarre
            $form = $this->createForm(PhaseaType::class, $projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}

                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
        return $this->renderForm('projet/phasea.html.twig', [
            'projet' => $projet,
            'form' => $form,
            'couts' => $projet->getFournisseur()->getProfils(),
        ]);
        }

        else if($projet->getPhase()->getId()==4) { //phase actuelle= cadrage
            $form = $this->createForm(PhasebType::class, $projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());
                if($projet->getDatereell1()!=null){
                    $daten=new DateLone();
                    $daten->setDatereel($projet->getDatel1());
                    $daten->setProjet($projet);
                    $projet->getDateLones()->add($daten);
                    $projet->setDatel1($projet->getDatereell1());
                }
                else{
                    $projet->setDatereell1($projet->getDatel1());
                }





            $this->getDoctrine()->getManager()->flush();
            $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phaseb.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==5) { //phase actuelle= en etude
            $form = $this->createForm(PhasecType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());
                if($projet->getDatereel0()!=null){
                    $daten=new DateZero();
                    $daten->setDatezero($projet->getDate0());
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                    $projet->setDate0($projet->getDatereel0());
                }
                else{
                    $projet->setDatereel0($projet->getDate0());
                }
                if($projet->getPhase()->getId()==1||($projet->getPhase()->getId()==2)) {
                    foreach ($projet->getCouts() as $c) {
                        $c->setNombreprofil(0);
                    }
                }





                $this->getDoctrine()->getManager()->flush();
                $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));

                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phasec.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),
            ]);
        }
        else if($projet->getPhase()->getId()==6) { //phase actuelle= construction
            $form = $this->createForm(PhasedType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());

                if($projet->getDatereel1()!=null){
                    $daten1=new DateOnePlus();
                    $daten1->setDate($projet->getDate1());
                    $daten1->setProjet($projet);
                    $projet->getDateOnePluses()->add($daten1);
                    $projet->setDate1($projet->getDatereel1());
                }
                else{
                    $projet->setDatereel1($projet->getDate1());
                }

                if($projet->getDatereel2()!=null){
                    $daten2=new DateTwo();
                    $daten2->setDatetwo($projet->getDate2());
                    $daten2->setProjet($projet);
                    $projet->getDateTwos()->add($daten2);
                    $projet->setDate2($projet->getDatereel2());
                }
                else{
                    $projet->setDatereel2($projet->getDate2());
                }

                if($projet->getDatereel3()!=null){
                    $daten3=new DataTrois();
                    $daten3->setDatet($projet->getDate3());
                    $daten3->setProjet($projet);
                    $projet->getDataTrois()->add($daten3);
                    $projet->setDate3($projet->getDatereel3());
                }
                else{
                    $projet->setDatereel3($projet->getDate3());
                }








                $this->getDoctrine()->getManager()->flush();
                $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phased.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==7) { //phase actuelle= en etude
            $form = $this->createForm(PhaseeType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();
                $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phasee.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==8) { //phase actuelle= recette
            $form = $this->createForm(PhasefType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phasef.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==9) { //phase actuelle= recette
            $form = $this->createForm(PhasegType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());

                $this->getDoctrine()->getManager()->flush();
                $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phaseg.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==1) { //abandonne

            $notifier->send(new Notification('Vous ne pouvez pas changer de phase car le projet est abandonné', ['browser']));
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }
        else if($projet->getPhase()->getId()==2) { //stand by

            $namephaseint=$projet->getHighestphase();
            $namephase = $phaseRepository->find( $namephaseint)->getName();

            $form = $this->createForm(PhasehType::class,$projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $projet->setPhase($phaseRepository->find( $projet->getHighestphase()));
                $projet->setDatemaj(new \DateTime());
                $this->getDoctrine()->getManager()->flush();
                $notifier->send(new Notification('Le projet n\'est plus en stand by', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phaseh.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'namephase'=>$namephase,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);

            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
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
