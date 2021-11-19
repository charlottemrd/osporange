<?php

namespace App\Controller;
use App\Entity\Bilanmensuel;
use App\Entity\Cout;
use App\Entity\DataTrois;
use App\Entity\DateLone;
use App\Entity\DateOnePlus;
use App\Entity\Datepvinterne;
use App\Entity\DateTwo;
use App\Entity\DateZero;
use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
use App\Entity\Infobilan;
use App\Entity\Profil;
use App\Entity\Projet;

use App\Entity\Pvinternes;
use App\Form\FicheliaisonType;
use App\Form\ModifyaType;
use App\Form\ModifybType;
use App\Form\ModifycType;
use App\Form\ModifydType;
use App\Form\ModifydfType;
use App\Form\ModifydeType;
use App\Form\ModifyieType;
use App\Form\PhasecType;
use App\Form\PhasedType;
use App\Form\PhasedfType;
use App\Form\PhaseeType;
use App\Form\PhasefType;
use App\Form\PhasegType;
use App\Form\PhasehType;
use App\Form\ProjetCoutType;
use App\Form\ProjetType;
use App\Form\SearchType;
use App\Form\PhaseaType;
use App\Form\PhasebType;
use App\Entity\SearchData;
use App\Repository\BilanmensuelRepository;
use App\Repository\CoutRepository;
use App\Repository\DatepvinterneRepository;
use App\Repository\FournisseurRepository;
use App\Repository\IdmonthbmRepository;
use App\Repository\InfobilanRepository;
use App\Repository\ModalitesRepository;
use App\Repository\ProjetRepository;
use App\Repository\ProfilRepository;
use App\Repository\PhaseRepository;
use App\Repository\PvinternesRepository;
use Doctrine\Persistence\ObjectManager;
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
use TCPDF;


use \setasign\Fpdi\FpdfTpl;
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
    public function new(ModalitesRepository $modalitesRepository ,DatepvinterneRepository $datepvinterneRepository ,InfobilanRepository $infobilanRepository ,CoutRepository $coutRepository, IdmonthbmRepository $idmonthbmRepository, ProjetRepository $projetRepository,ProfilRepository $profilRepository,Request $request,NotifierInterface $notifier): Response
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
            $com=$projet->getCommentaires();
            foreach ($com as $c){
                if ($c->getDate()==null){
                    $c->setDate(new \DateTime());
                }
            }

            if(($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)||($projet->getPhase()->getId()==3)||($projet->getPhase()->getId()==4)||($projet->getPhase()->getId()==5)){
                foreach ($projet->getModalites() as $myp){
                    $projet->removeModalite($myp);
                }
                $projet->setGaranti(null);
                $projet->setDebit1bm(null);
                $projet->setDebit2bm(null);
                $projet->setDebit3bm(null);
                $projet->setIsfinish(false);
                $projet->setIseligibletobm(false);
            }

            //to change after
            $fournisseur=$projet->getFournisseur();
            $profils=$fournisseur->getProfils();
            foreach ($profils as $p){
                $cout= new Cout();
                $cout->setProfil($p);
                $cout->setNombreprofil(0);
                $cout->setProjet($projet);
                $projet->getCouts()->add($cout);
            }

            $mod=$projet->getModalites();
            foreach ($mod as $m){
                if ($m->getConditionsatisfield()==null){
                    $m->setConditionsatisfield(false);
                    $m->setIsapproved(false);
                    $m->setIsencours(false);
                }
            }
            $myphase=$projet->getPhase()->getId();
            if($myphase>=6){
                if ($projet->getPaiement()->getId()==2){
                    $dateactuelle=new \DateTime();
                    $moisencours=date_format($dateactuelle, 'm');
                    $anneeencours=date_format($dateactuelle, 'Y');
                    //$idmonthbmpasse=$idmonthbmRepository->ownmonthfournisseur($moisencours,$anneeencours,$projet->getFournisseur()->getId());
                    $pvinternepass=$datepvinterneRepository->owndatepv($moisencours,$anneeencours);
                    if($pvinternepass){ //on cree un pv interne avec date= pv interne pass

                        $pvinterne=new Pvinternes();
                        $pvinterne->setProjet($projet);
                        $pvinterne->setDate($pvinternepass);
                        $pvinterne->setIsmodified(false);
                        $pvinterne->setIsvalidate(false);
                        $pvinterne->setPourcentage(0);
                        $this->getDoctrine()->getManager()->persist($pvinterne);


                }
                    else{ // on cree tout

                        $datepvinterne=new Datepvinterne();
                        $datepvinterne->setDatemy(new \DateTime());
                        $pvinterne=new Pvinternes();
                        $pvinterne->setProjet($projet);
                        $pvinterne->setDate($datepvinterne);
                        $pvinterne->setIsmodified(false);
                        $pvinterne->setIsvalidate(false);
                        $pvinterne->setPourcentage(0);
                        $this->getDoctrine()->getManager()->persist($datepvinterne);
                        $this->getDoctrine()->getManager()->persist($pvinterne);


                    }
                }// si paiement = bilan mensuel


            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();

            if(($projet->getPhase()->getId()>=6)&&($projet->getPaiement()->getId()==2)){
                $ml=$modalitesRepository->findBy(array('projet'=>$projet),array('pourcentage'=>'ASC'));
                $ui=0;
                foreach ($ml as $k){
                    $k->setRank($ui+1);
                    $ui=$ui+1;
                }
                $entityManager->flush();
                $mlm=$modalitesRepository->findOneBy(array('projet'=>$projet,'rank'=>1));
                $mlm->setIsencours(true);
                $entityManager->flush();
            }


            if(($myphase==6)||($myphase==7)||($myphase==8)||($myphase==9)||($myphase==10))
            {


                return $this->redirectToRoute('projet_cout', ['projet'=>$projet,'id'=>$projet->getId()], Response::HTTP_SEE_OTHER);

            }
            else{
                $notifier->send(new Notification('Le projet a bien été ajouté', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
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

    #[Route('/{id}/cout', name: 'projet_cout', methods: ['GET', 'POST'])]
    public function cout(BilanMensuelController $bilanMensuelController,  ProfilRepository $profilRepository,BilanmensuelRepository $bilanmensuelRepository, InfobilanRepository $infobilanRepository, CoutRepository $coutRepository, IdmonthbmRepository $idmonthbmRepository, Request $request, Projet $projet,NotifierInterface $notifier): Response
    {
        $mform = $this->createForm(ProjetCoutType::class, $projet);
        $mform->handleRequest($request);
        if ($mform->isSubmitted() && $mform->isValid()) {
            if($projet->getPaiement()->getId()==1){
                $projet->setIsfinish(false);
                $projet->setIseligibletobm(true);
                $dateactuelle=new \DateTime();
                $moisencours=date_format($dateactuelle, 'm');
                $anneeencours=date_format($dateactuelle, 'Y');
                $idmonthbmpasse=$idmonthbmRepository->ownmonthfournisseur($moisencours,$anneeencours,$projet->getFournisseur()->getId());
                if($idmonthbmpasse){ //on ajout direct
                    $bilanadd=new Bilanmensuel();
                    $bilanadd->setDatemaj(new \DateTime());
                    $mybilan=$bilanadd->getId();
                    $bilanadd->setProjet($projet);
                    $bilanadd->setHavebeenmodified(0);
                    $bilanadd->setIdmonthbm($idmonthbmpasse);
                    $this->getDoctrine()->getManager()->persist($bilanadd);

                    $profilsfournisseur=$projet->getFournisseur()->getProfils();
                    foreach ($profilsfournisseur as $po){
                        $info1=new Infobilan();
                     //   $pcom=whichpoc($projet);
                        $mth=manymonthleft($projet,$idmonthbmpasse);
                        $sxz= proposeTGIM( $infobilanRepository, $coutRepository,  $idmonthbmpasse,$po, $projet, $mth);

                        $info1->setNombreprofit($sxz);
                        $info1->setProfil($po);
                        $info1->setBilanmensuel($bilanadd);
                        $this->getDoctrine()->getManager()->persist($info1);
                    }
                    $this->getDoctrine()->getManager()->flush();
                }else{
                    $infmon=new Idmonthbm();
                    $infmon->setMonthyear(new \DateTime);
                    $infmon->setIsaccept(0);
                    $infmon->setFournisseur($projet->getFournisseur());
                    $this->getDoctrine()->getManager()->persist($infmon);

                    $bilanadd=new Bilanmensuel();
                    $mybilan=$bilanadd->getId();
                    $bilanadd->setDatemaj(new \DateTime());
                    $bilanadd->setProjet($projet);
                    $bilanadd->setHavebeenmodified(0);
                    $bilanadd->setIdmonthbm($infmon);
                    $this->getDoctrine()->getManager()->persist($bilanadd);

                    $profilsfournisseur=$projet->getFournisseur()->getProfils();
                    foreach ($profilsfournisseur as $po){
                        $info1=new Infobilan();
                    //    $pcom=whichpoc($projet);
                        $mth=manymonthleft($projet,$infmon);
                        $sxz= proposeTGIM( $infobilanRepository, $coutRepository,  $infmon,$po, $projet, $mth);
                        $info1->setNombreprofit($sxz);
                        $info1->setProfil($po);
                        $info1->setBilanmensuel($bilanadd);
                        $this->getDoctrine()->getManager()->persist($info1);
                    }
                    $this->getDoctrine()->getManager()->flush();

                }

            }
            else{
                $projet->setIseligibletobm(false);
            }
             $this->getDoctrine()->getManager()->flush();


            $notifier->send(new Notification('Le projet a bien été ajouté', ['browser']));
            return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('projet/projetcout.html.twig', [
            'projet' => $projet,
            'mform' => $mform,
            'couts' => $projet->getFournisseur()->getProfils(),
            'fournisseur' => $projet->getFournisseur(),
        ]);
    }



    #[Route('/{id}', name: 'projet_show', methods: ['GET'])]
    public function show(Projet $projet, InfobilanRepository $infobilanRepository, IdmonthbmRepository $idmonthbmRepository, BilanmensuelRepository $bilanmensuelRepository): Response
    {
        if(($projet->getPhase()->getId()==3)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==3))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==3))){//non demarre
            return $this->render('projet/showa.html.twig', [
                'projet' => $projet,
                'commentaires'=>$projet->getCommentaires(),
            ]);
        }
        else if(($projet->getPhase()->getId()==4)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==4))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==4))){ //cadrage
            return $this->render('projet/showb.html.twig', [
                'projet' => $projet,
                'date_lones'=>$projet->getDateLones(),
                'commentaires'=>$projet->getCommentaires(),
            ]);
        }
        else if(($projet->getPhase()->getId()==5)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==5))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==5))){ //etude
            return $this->render('projet/showc.html.twig', [
                'projet' => $projet,
                'date_lones'=>$projet->getDateLones(),
                'date_zeros'=>$projet->getDateZeros(),
                'commentaires'=>$projet->getCommentaires(),
            ]);
        }
        else if(($projet->getPhase()->getId()==6)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==6))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==6))){ //consctruction
            $idmonthbm=$idmonthbmRepository->ownprojet($projet->getId());
            //$idmonthbm=$idmonthbmRepository->ownprojet($projet->getId());
            $profit=[];
            if($idmonthbm) {
                $profit = $idmonthbm[0]->getBilanMensuels()[0]->getInfobilans();
            }



            return $this->render('projet/showdf.html.twig', [
                'idmonthbms'=>$idmonthbm,
                'bilas'=>$profit,
                'projet' => $projet,
                'idprojet'=>$projet->getId(),
                'date_lones'=>$projet->getDateLones(),
                'date_zeros'=>$projet->getDateZeros(),
                'date_one_pluses'=>$projet->getDateOnePluses(),
                'date_twos'=>$projet->getDateTwos(),
                'data_troises'=>$projet->getDataTrois(),
                'couts'=>$projet->getCouts(),
                'modalites'=>$projet->getModalites(),

                'profils' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),
                'commentaires'=>$projet->getCommentaires(),

            ]);
        }
        else if(($projet->getPhase()->getId()==7)||(($projet->getPhase()->getId()==1)&&($projet->getHighestphase()==7))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==7))){ //consctruction

            $idmonthbm=$idmonthbmRepository->ownprojet($projet->getId());
            $profit=[];
            if($idmonthbm) {
                $profit = $idmonthbm[0]->getBilanMensuels()[0]->getInfobilans();
            }

            return $this->render('projet/showde.html.twig', [
                'idmonthbms'=>$idmonthbm,
                'bilas'=>$profit,
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
                'commentaires'=>$projet->getCommentaires(),

            ]);
        }

        else if(($projet->getPhase()->getId()==8)||(($projet->getPhase()->getId()==8)&&($projet->getHighestphase()==1))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==8))){ //test
            $idmonthbm=$idmonthbmRepository->ownprojet($projet->getId());
            $profit=[];
            if($idmonthbm) {
                $profit = $idmonthbm[0]->getBilanMensuels()[0]->getInfobilans();
            }


            return $this->render('projet/showdg.html.twig', [
                'idmonthbms'=>$idmonthbm,
                'bilas'=>$profit,
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
                'commentaires'=>$projet->getCommentaires(),

            ]);
        }
        else if(($projet->getPhase()->getId()==9)||(($projet->getPhase()->getId()==9)&&($projet->getHighestphase()==1))||(($projet->getPhase()->getId()==2)&&($projet->getHighestphase()==9))){ //test
            return $this->render('projet/showdg.html.twig', [
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
                'commentaires'=>$projet->getCommentaires(),

            ]);
        }
        else
        {
            $idmonthbm=$idmonthbmRepository->ownprojet($projet->getId());
            $profit=[];
            if($idmonthbm) {
                $profit = $idmonthbm[0]->getBilanMensuels()[0]->getInfobilans();
            }

            return $this->render('projet/showe.html.twig', [
            'idmonthbms'=>$idmonthbm,
            'bilas'=>$profit,
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
            'commentaires'=>$projet->getCommentaires(),
        ]);
        }


    }



    #[Route('/{id}/edit', name: 'projet_edit', methods: ['GET', 'POST'])]
    public function edit(ModalitesRepository $modalitesRepository, Request $request, Projet $projet,NotifierInterface $notifier): Response
    {
        if($projet->getPhase()->getId()==3) { //phase actuelle= non demarre
            $form = $this->createForm(ModifyaType::class, $projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

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

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
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

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

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

        else if($projet->getPhase()->getId()==6) { //phase actuelle= construction 1
            $datereel0avant=$projet->getDatereel0();
            $datereell1avant=$projet->getDatereell1();
            $date1avant=$projet->getDate1();
            $date2avant=$projet->getDate2();
            $date3avant=$projet->getDate3();
            $lastpourcentage=0;
            $modfini=$modalitesRepository->findBy(array('projet'=>$projet,'isapproved'=>true),array('pourcentage'=>'DESC'));
            if(sizeof($modfini,COUNT_NORMAL)!=0){
                $lastpourcentage=$modfini[0]->getPourcentage();
            }
            else{
                $lastpourcentage=0;
            }

            $form = $this->createForm(ModifydType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mod=$projet->getModalites();
                foreach ($mod as $m){
                    if ($m->getConditionsatisfield()==null){
                        $m->setConditionsatisfield(false);
                        $m->setIsapproved(false);
                        $m->setIsencours(false);
                    }
                }

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

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
                'modalis'=>$projet->getModalites(),
                'fournisseur'=>$projet->getFournisseur(),
            ]);
        }

        else if($projet->getPhase()->getId()==7) { //phase actuelle= construction 2
            $datereel0avant=$projet->getDatereel0();
            $datereell1avant=$projet->getDatereell1();
            $datereel1avant=$projet->getDatereel1();
            $date2avant=$projet->getDate2();
            $date3avant=$projet->getDate3();
            $form = $this->createForm(ModifydeType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mod=$projet->getModalites();
                foreach ($mod as $m){
                    if ($m->getConditionsatisfield()==null){
                        $m->setConditionsatisfield(false);
                        $m->setIsapproved(false);
                        $m->setIsencours(false);
                    }
                }

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

                if(( $datereell1avant!=$projet->getDatereell1())&&$datereell1avant!=null) //datereel t -1
                {
                    $daten2=new DateLone();
                    $daten2->setDatereel($datereell1avant);
                    $daten2->setProjet($projet);
                    $projet->getDateLones()->add($daten2);

                    $projet->setDatel1($projet->getDatereell1());
                }


                if( $datereel0avant!=$projet->getDatereel0()&&$datereel0avant!=null) //datereel t 0
                {
                    $daten=new DateZero();
                    $daten->setDatezero($datereel0avant);
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                    $projet->setDate0($projet->getDatereel0());
                }

                if($datereel1avant!=$projet->getDatereel1()&&$datereel1avant!=null){
                    $daten3=new DateOnePlus();
                    $daten3->setDate($datereel1avant);
                    $daten3->setProjet($projet);
                    $projet->getDateOnePluses()->add($daten3);
                    $projet->setDate1($projet->getDatereel1());
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
            return $this->renderForm('projet/modifyde.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),
                'pourapproved'=>$lastpourcentage,
            ]);
        }

        else if($projet->getPhase()->getId()==8) { //phase actuelle= test
            $datereel0avant=$projet->getDatereel0();
            $datereell1avant=$projet->getDatereell1();
            $datereel1avant=$projet->getDatereel1();
            $datereel2avant=$projet->getDatereel2();
            $date3avant=$projet->getDate3();
            $form = $this->createForm(ModifydfType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mod=$projet->getModalites();
                foreach ($mod as $m){
                    if ($m->getConditionsatisfield()==null){
                        $m->setConditionsatisfield(false);
                        $m->setIsapproved(false);
                        $m->setIsencours(false);
                    }
                }

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

                if(( $datereell1avant!=$projet->getDatereell1())&&$datereell1avant!=null) //datereel t -1
                {
                    $daten2=new DateLone();
                    $daten2->setDatereel($datereell1avant);
                    $daten2->setProjet($projet);
                    $projet->getDateLones()->add($daten2);

                    $projet->setDatel1($projet->getDatereell1());
                }


                if( $datereel0avant!=$projet->getDatereel0()&&$datereel0avant!=null) //datereel t 0
                {
                    $daten=new DateZero();
                    $daten->setDatezero($datereel0avant);
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                    $projet->setDate0($projet->getDatereel0());
                }

                if($datereel1avant!=$projet->getDatereel1()&&$datereel1avant!=null){
                    $daten3=new DateOnePlus();
                    $daten3->setDate($datereel1avant);
                    $daten3->setProjet($projet);
                    $projet->getDateOnePluses()->add($daten3);
                    $projet->setDate1($projet->getDatereel1());
                }

                if($datereel2avant!=$projet->getDatereel2()&&$datereel2avant!=null){
                    $daten4=new DateTwo();
                    $daten4->setDatetwo($datereel2avant);
                    $daten4->setProjet($projet);
                    $projet->getDateTwos()->add($daten4);
                    $projet->setDate2($projet->getDatereel2());
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
            return $this->renderForm('projet/modifydf.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),
            ]);
        }

        else if($projet->getPhase()->getId()==9) { //phase actuelle= recette
            $datereel0avant=$projet->getDatereel0();
            $datereell1avant=$projet->getDatereell1();
            $datereel1avant=$projet->getDatereel1();
            $datereel2avant=$projet->getDatereel2();
            $date3avant=$projet->getDate3();
            $form = $this->createForm(ModifydfType::class, $projet);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $mod=$projet->getModalites();
                foreach ($mod as $m){
                    if ($m->getConditionsatisfield()==null){
                        $m->setConditionsatisfield(false);
                        $m->setIsapproved(false);
                        $m->setIsencours(false);
                    }
                }

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

                if(( $datereell1avant!=$projet->getDatereell1())&&$datereell1avant!=null) //datereel t -1
                {
                    $daten2=new DateLone();
                    $daten2->setDatereel($datereell1avant);
                    $daten2->setProjet($projet);
                    $projet->getDateLones()->add($daten2);

                    $projet->setDatel1($projet->getDatereell1());
                }


                if( $datereel0avant!=$projet->getDatereel0()&&$datereel0avant!=null) //datereel t 0
                {
                    $daten=new DateZero();
                    $daten->setDatezero($datereel0avant);
                    $daten->setProjet($projet);
                    $projet->getDateZeros()->add($daten);
                    $projet->setDate0($projet->getDatereel0());
                }

                if($datereel1avant!=$projet->getDatereel1()&&$datereel1avant!=null){
                    $daten3=new DateOnePlus();
                    $daten3->setDate($datereel1avant);
                    $daten3->setProjet($projet);
                    $projet->getDateOnePluses()->add($daten3);
                    $projet->setDate1($projet->getDatereel1());
                }

                if($datereel2avant!=$projet->getDatereel2()&&$datereel2avant!=null){
                    $daten4=new DateTwo();
                    $daten4->setDatetwo($datereel2avant);
                    $daten4->setProjet($projet);
                    $projet->getDateTwos()->add($daten4);
                    $projet->setDate2($projet->getDatereel2());
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
            return $this->renderForm('projet/modifydf.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
                'fournisseur'=>$projet->getFournisseur(),
            ]);
        }

        else if(($projet->getPhase()->getId()==10)) { //phase actuelle= prod
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

                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }

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
                    $projet->setDate3($projet->getDatereel3());
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
                'fournisseur'=>$projet->getFournisseur(),
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
    public function phase(ModalitesRepository $modalitesRepository, IdmonthbmRepository $idmonthbmRepository, ProfilRepository $profilRepository, InfobilanRepository $infobilanRepository,CoutRepository $coutRepository,  BilanmensuelRepository $bilanmensuelRepository, PhaseRepository $phaseRepository,Request $request, Projet $projet,NotifierInterface $notifier): Response
    {
        if($projet->getPhase()->getId()==3) { //phase actuelle= non demarre
            $form = $this->createForm(PhaseaType::class, $projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                $projet->setDatemaj(new \DateTime());
                $coma=$projet->getCommentaires();
                foreach ($coma as $com){
                    if ($com->getDate()==null){
                        $com->setDate(new \DateTime());
                    }
                }
                if($projet->getHighestphase()<$projet->getPhase()->getRang())
                {
                    $projet->setHighestphase($projet->getPhase()->getRang());
                }




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
                if(($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)){
                    foreach ($projet->getModalites() as $myp){
                        $projet->removeModalite($myp);
                    }
                    $projet->setGaranti(null);
                    $projet->setDebit1bm(null);
                    $projet->setDebit2bm(null);
                    $projet->setDebit3bm(null);
                }
                if($projet->getPhase()->getId()==6){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(true);
                        $dateactuelle=new \DateTime();
                        $moisencours=date_format($dateactuelle, 'm');
                        $anneeencours=date_format($dateactuelle, 'Y');
                        $idmonthbmpasse=$idmonthbmRepository->ownmonthfournisseur($moisencours,$anneeencours,$projet->getFournisseur()->getId());
                        if($idmonthbmpasse){ //on ajout direct

                            $bilanadd=new Bilanmensuel();
                            $mybilan=$bilanadd->getId();
                            $bilanadd->setDatemaj(new \DateTime());
                            $bilanadd->setProjet($projet);
                            $bilanadd->setHavebeenmodified(0);
                            $bilanadd->setIdmonthbm($idmonthbmpasse);
                            $this->getDoctrine()->getManager()->persist($bilanadd);

                            $profilsfournisseur=$projet->getFournisseur()->getProfils();
                            foreach ($profilsfournisseur as $po){
                                $info1=new Infobilan();
                                //$pcom=whichpoc($projet);
                                $mth=manymonthleft($projet,$idmonthbmpasse);
                                $sxz= proposeTGIM( $infobilanRepository, $coutRepository,  $idmonthbmpasse,$po, $projet, $mth);
                                $info1->setNombreprofit($sxz);
                                $info1->setProfil($po);
                                $info1->setBilanmensuel($bilanadd);
                                $this->getDoctrine()->getManager()->persist($info1);
                            }






                        }else{
                            $infmon=new Idmonthbm();
                            $infmon->setMonthyear(new \DateTime);
                            $infmon->setIsaccept(0);
                            $infmon->setFournisseur($projet->getFournisseur());
                            $this->getDoctrine()->getManager()->persist($infmon);

                            $bilanadd=new Bilanmensuel();
                            $mybilan=$bilanadd->getId();
                            $bilanadd->setDatemaj(new \DateTime());
                            $bilanadd->setProjet($projet);
                            $bilanadd->setHavebeenmodified(0);
                            $bilanadd->setIdmonthbm($infmon);
                            $this->getDoctrine()->getManager()->persist($bilanadd);

                            $profilsfournisseur=$projet->getFournisseur()->getProfils();
                            foreach ($profilsfournisseur as $po){
                                $info1=new Infobilan();
                             //   $pcom=whichpoc($projet);
                                $mth=manymonthleft($projet,$infmon);
                                $sxz= proposeTGIM( $infobilanRepository, $coutRepository,  $infmon,$po, $projet, $mth);
                                $info1->setNombreprofit($sxz);
                                $info1->setProfil($po);
                                $info1->setBilanmensuel($bilanadd);
                                $this->getDoctrine()->getManager()->persist($info1);
                            }
                            $this->getDoctrine()->getManager()->flush();

                        }

                    }
                }

                $this->getDoctrine()->getManager()->flush();



                if($projet->getPhase()->getId()==6) {
                    if ($projet->getPaiement()->getId() == 1) {
                        $couttotal = coutprojet($projet);
                        $anciensbilans = $infobilanRepository->searchinfobilandebitefalse($projet->getId());

                        if (sizeof($anciensbilans, COUNT_NORMAL) == 0) {
                            $coutdebit = 0;
                        } else {
                            $coutdebit = 0;
                            foreach ($anciensbilans as $anciensbilansdeb) {
                                $nb = $anciensbilansdeb->getNombreprofit();
                                $profitt = $anciensbilansdeb->getProfil();
                                $pmd = $profilRepository->findOneBy(array('id' => $profitt))->getTarif();
                                $coutdebit = $coutdebit + ($nb * $pmd);
                            }
                        }
                        $pourcentage = whichpoc($projet);
                        if ($coutdebit > $couttotal * ($pourcentage / 100)) {
                            $inf = $bilanmensuelRepository->findOneBy(array('id' => $mybilan));
                            foreach ($inf->getInfobilans() as $fo) {
                                $fo->setNombreprofit(0);
                            }
                            $this->getDoctrine()->getManager()->flush();
                        }
                    }
                    if(($projet->getPaiement()->getId()==2)){
                        $ml=$modalitesRepository->findBy(array('projet'=>$projet),array('pourcentage'=>'ASC'));
                        $ui=0;
                        foreach ($ml as $k){
                            $k->setRank($ui+1);
                            $ui=$ui+1;
                        }
                        $this->getDoctrine()->getManager()->flush();
                        $mlm=$modalitesRepository->findOneBy(array('projet'=>$projet,'rank'=>1));
                        $mlm->setIsencours(true);
                        $this->getDoctrine()->getManager()->flush();
                    }


                }




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
        else if($projet->getPhase()->getId()==6) { //phase actuelle= construction : en conception
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
                if (($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(false);
                        $idmonthy=$idmonthbmRepository->findBy(array('fournisseur'=>$projet->getFournisseur()->getId(),'isaccept'=>0));
                        foreach ($idmonthy as $idmonthies){
                            $bmtodelete=$idmonthies->getBilanMensuels();
                            foreach ($bmtodelete as $bmtodeletes){
                                if($bmtodeletes->getProjet()->getId()==$projet->getId()){
                                    foreach ($bmtodeletes->getInfobilans() as $infs){
                                        $bmtodeletes->removeInfobilan($infs);
                                    }
                                    $idmonthies->removeBilanMensuel($bmtodeletes);
                                }
                            }

                        }
                    }
                }

                /*if($projet->getDatereel2()!=null){
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
                }*/

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

        else if($projet->getPhase()->getId()==7) { //phase actuelle= construction : en conception 2
            $form = $this->createForm(PhasedfType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());

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
                if (($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(false);
                        $idmonthy=$idmonthbmRepository->findBy(array('fournisseur'=>$projet->getFournisseur()->getId(),'isaccept'=>0));
                        foreach ($idmonthy as $idmonthies){
                            $bmtodelete=$idmonthies->getBilanMensuels();
                            foreach ($bmtodelete as $bmtodeletes){
                                if($bmtodeletes->getProjet()->getId()==$projet->getId()){
                                    $bmtodeletes->removeInfobilan();
                                    $idmonthies->removeBilanMensuel($bmtodeletes);
                                }
                            }

                        }
                    }
                }

                $this->getDoctrine()->getManager()->flush();
                $notifier->send(new Notification('Le projet a bien changé de phase', ['browser']));
                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phasedf.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==8) { //phase actuelle= test
            $form = $this->createForm(PhaseeType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());
                if (($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(false);
                        $idmonthy=$idmonthbmRepository->findBy(array('fournisseur'=>$projet->getFournisseur()->getId(),'isaccept'=>0));
                        foreach ($idmonthy as $idmonthies){
                            $bmtodelete=$idmonthies->getBilanMensuels();
                            foreach ($bmtodelete as $bmtodeletes){
                                if($bmtodeletes->getProjet()->getId()==$projet->getId()){
                                    $bmtodeletes->removeInfobilan();
                                    $idmonthies->removeBilanMensuel($bmtodeletes);
                                }
                            }

                        }
                    }
                }

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

        else if($projet->getPhase()->getId()==9) { //phase actuelle= recette
            $form = $this->createForm(PhasefType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());
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
                if (($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(false);
                        $idmonthy=$idmonthbmRepository->findBy(array('fournisseur'=>$projet->getFournisseur()->getId(),'isaccept'=>0));
                        foreach ($idmonthy as $idmonthies){
                            $bmtodelete=$idmonthies->getBilanMensuels();
                            foreach ($bmtodelete as $bmtodeletes){
                                if($bmtodeletes->getProjet()->getId()==$projet->getId()){
                                    $bmtodeletes->removeInfobilan();
                                    $idmonthies->removeBilanMensuel($bmtodeletes);
                                }
                            }

                        }
                    }
                }

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('projet_index', [], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('projet/phasef.html.twig', [
                'projet' => $projet,
                'form' => $form,
                'couts' => $projet->getFournisseur()->getProfils(),
            ]);
        }

        else if($projet->getPhase()->getId()==10) { //phase actuelle= prod
            $form = $this->createForm(PhasegType::class,$projet);
            $form->handleRequest($request);



            if ($form->isSubmitted() && $form->isValid()) {
                if($projet->getHighestphase()<$projet->getPhase()->getRang()){
                    $projet->setHighestphase($projet->getPhase()->getRang());}
                $projet->setDatemaj(new \DateTime());
                if (($projet->getPhase()->getId()==1)||($projet->getPhase()->getId()==2)){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(false);
                        $idmonthy=$idmonthbmRepository->findBy(array('fournisseur'=>$projet->getFournisseur()->getId(),'isaccept'=>0));
                        foreach ($idmonthy as $idmonthies){
                            $bmtodelete=$idmonthies->getBilanMensuels();
                            foreach ($bmtodelete as $bmtodeletes){
                                if($bmtodeletes->getProjet()->getId()==$projet->getId()){
                                    $bmtodeletes->removeInfobilan();
                                    $idmonthies->removeBilanMensuel($bmtodeletes);

                                }
                            }

                        }
                    }
                }


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
                if($projet->getHighestphase()>=6){
                    if($projet->getPaiement()->getId()==1){
                        $projet->setIseligibletobm(true);
                        $dateactuelle=new \DateTime();
                        $moisencours=date_format($dateactuelle, 'm');
                        $anneeencours=date_format($dateactuelle, 'Y');
                        $idmonthbmpasse=$idmonthbmRepository->ownmonthfournisseur($moisencours,$anneeencours,$projet->getFournisseur()->getId());
                        if($idmonthbmpasse){ //on ajout direct
                            $bilanadd=new Bilanmensuel();
                            $mybilan=$bilanadd->getId();
                            $bilanadd->setDatemaj(new \DateTime());
                            $bilanadd->setProjet($projet);
                            $bilanadd->setHavebeenmodified(0);
                            $bilanadd->setIdmonthbm($idmonthbmpasse);
                            $this->getDoctrine()->getManager()->persist($bilanadd);

                            $profilsfournisseur=$projet->getFournisseur()->getProfils();
                            foreach ($profilsfournisseur as $po){
                                $info1=new Infobilan();
                               // $pcom=whichpoc($projet);
                                $mth=manymonthleft($projet,$idmonthbmpasse);
                                $sxz= proposeTGIM( $infobilanRepository, $coutRepository,  $idmonthbmpasse,$po, $projet,$mth);
                                $info1->setNombreprofit($sxz);
                                $info1->setProfil($po);
                                $info1->setBilanmensuel($bilanadd);
                                $this->getDoctrine()->getManager()->persist($info1);
                            }

                        }else {
                            $infmon = new Idmonthbm();
                            $infmon->setMonthyear(new \DateTime);
                            $infmon->setIsaccept(0);
                            $infmon->setFournisseur($projet->getFournisseur());
                            $this->getDoctrine()->getManager()->persist($infmon);

                            $bilanadd = new Bilanmensuel();
                            $mybilan=$bilanadd->getId();
                            $bilanadd->setDatemaj(new \DateTime());
                            $bilanadd->setProjet($projet);
                            $bilanadd->setHavebeenmodified(0);
                            $bilanadd->setIdmonthbm($infmon);
                            $this->getDoctrine()->getManager()->persist($bilanadd);

                            $profilsfournisseur = $projet->getFournisseur()->getProfils();
                            foreach ($profilsfournisseur as $po) {
                                $info1 = new Infobilan();
                               // $pcom=whichpoc($projet);
                                $mth=manymonthleft($projet,$infmon);
                                $sxz= proposeTGIM( $infobilanRepository, $coutRepository,  $infmon,$po, $projet, $mth);
                                $info1->setNombreprofit($sxz);
                                $info1->setProfil($po);
                                $info1->setBilanmensuel($bilanadd);
                                $this->getDoctrine()->getManager()->persist($info1);
                            }
                        }
                    }
                }
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




    #[Route('/{id}/fichefl', name: 'projet_fichefl', methods: ['GET', 'POST'])]
    public function ficheliaison(Request $request, Projet $projet, FournisseurRepository $fournisseurRepository): Response
    {
        $form = $this->createForm(FicheliaisonType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $referencefl = $form->get('reference')->getData();
            $prioritefl = $form->get('priorite')->getViewData();
            $dateemise = $form->get('dateemis')->getViewData();
            //$dateemisfl = 'vgh';
            $dateemisfl=date("d/m/Y",strtotime($dateemise));
            $emetteurfl = $form->get('emetteur')->getData();
            $sujetfl = $form->get('sujet')->getData();
            $descriptionfl = $form->get('description')->getData();
            $piecejointesfl = $form->get('piecejointes')->getData();
            try {
                $this->createfl($referencefl, $prioritefl, $dateemisfl, $emetteurfl, $sujetfl, $descriptionfl, $piecejointesfl);
            }
            catch (IOException $exception){}

        }
        return $this->renderForm('projet/fichefl.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    function createfl($referencefl,$prioritefl,$dateemisfl,$emetteurfl,$sujetfl,$descriptionfl,$piecejointesfl){

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetTitle('Fiche de liaison');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT,  PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM,PDF_MARGIN_LEFT,  PDF_MARGIN_RIGHT);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage();

        $tbl ='';
        $tbl='<p>Référence (FL_CP_aaaammjj_nn)   :  '.$referencefl.'</p>
           <table cellspacing="0" cellpadding="0" border="0" >
         <tr>
           <td colspan="2"  style=" background-color: lightgrey;border-top: black solid 1px;border-left: black solid 1px;border-right: black solid 1px" >
        </td>
        </tr>
    <tr>
        
        <td colspan="2" style=" background-color: lightgrey; text-align: center;border-left: black solid 1px;border-right: black solid 1px"><p style="font-weight: bold">ENTITE EMETTRICE</p></td>
    </tr>
    
      <tr>
      <td colspan="2"  style=" background-color: lightgrey;border-bottom: black solid 1px;border-left: black solid 1px;border-right: black solid 1px" >
        </td>
      
    </tr>
    
    <tr>
   <td colspan="2" style="border-right: black solid 1px; border-left: black solid 1px">
      
</td>
    </tr>
    
    <tr>
        <td style="border-left: black solid 1px">
     
       Date d\'émission : '.$dateemisfl.' </td>';

        if ($prioritefl=='1') {
            $tbl.='<td style="border-right: black solid 1px" >
<br />
Priorité:
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Haute </label>
                <input type="checkbox" name="agree" value="0" disabled="disabled" readonly="readonly"/> <label for="agree">Moyenne </label>
                <input type="checkbox" name="agree" value="1" checked="checked" disabled="disabled" readonly="readonly"/> <label for="agree">Basse</label><br>
                </td>





'; }

        else if ($prioritefl=='2') {

            $tbl.='<td style="border-right: black solid 1px" >
<br />
Priorité:
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Haute </label>
                <input type="checkbox" name="agree" value="1" checked="checked" disabled="disabled" readonly="readonly"/> <label for="agree">Moyenne </label>
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Basse</label><br>
                </td>
'; }

        else{

            $tbl.='<td  style="border-right: black solid 1px">
Priorité:
                <input type="checkbox" name="agree" value="1" checked="checked" disabled="disabled" readonly="readonly"/> <label for="agree">Haute </label>
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Moyenne </label>
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Basse</label><br>
                </td>
'; }

        $tbl.= '</tr>
<tr  >
<td colspan="2"  style="border-left: black solid 1px;border-right: black solid 1px" >
       
        Emetteur : &nbsp; &nbsp; &thinsp; &thinsp; 
        '.$emetteurfl.'<br /></td>
 </tr>
 <tr>
  <td colspan="2"  style="border-left: black solid 1px;border-right: black solid 1px" >
        Sujet : &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &thinsp;
        '.$sujetfl.'<br /></td>
 </tr>
 
  <tr>
 <td colspan="2"  style="border-left: black solid 1px;border-right: black solid 1px" >
       
        Description : &nbsp; &nbsp; 
        '.$descriptionfl.' <br /></td>
 </tr>
 
  <tr>
  <td colspan="2"  style="border-bottom: black solid 1px;border-left: black solid 1px;border-right: black solid 1px" >
       
         Pièces jointes : 
        '.$piecejointesfl.' <br /></td>
 </tr>
 
</table>

<table cellspacing="0" cellpadding="0" border="0" >
         <tr>
           <td colspan="2"  >
        </td>
        </tr>
         <tr>
           <td colspan="2"   >
        </td>
        </tr>
         <tr>
           <td colspan="2" >
        </td>
        </tr>
</table>



<form method="post" action="http://localhost/printvars.php" >
<table cellspacing="0" cellpadding="0" border="0" >
   <tr>
           <td colspan="2"  style=" background-color: lightgrey;border-top: black solid 1px;border-left: black solid 1px;border-right: black solid 1px;" >
        </td>
        </tr>
    <tr>
        
        <td colspan="2" style=" background-color: lightgrey;text-align: center;border-left: black solid 1px;border-right: black solid 1px"><p style="font-weight: bold">ENTITE RECEPTRICE</p></td>
    </tr>
    
      <tr>
      <td colspan="2"  style=" background-color: lightgrey;border-left: black solid 1px;border-right: black solid 1px;" >
        </td>
      
    </tr>
     <tr>
   <td colspan="2" style="border-left: black solid 1px;border-right: black solid 1px;border-top: black solid 1px">
      
</td>
    </tr>
    <tr  >
 <td colspan="2" style="text-align: left;border-left: black solid 1px;border-right: black solid 1px;">
<label for="name">
        Date de réception :</label> <input type="text" name="name" value="" size="6" maxlength="6" />/<input type="text" name="name2" value="" size="6" maxlength="6" />/<input type="text" name="name3" value="" size="6" maxlength="6" /><br />

</td>
 </tr>
 <tr >
 <td colspan="2" rowspan="1" style="text-align: left;border-left: black solid 1px;border-right: black solid 1px;">
<label for="name">
        Récepteur:</label> <textarea cols="40" rows="1"name="name0" value="" /><br />
</td>
 </tr>
  <tr >
 <td colspan="2" rowspan="4" style="text-align: left;border-left: black solid 1px;border-bottom:  black solid 1px;border-right: black solid 1px;">
<label for="name">
        Réponse:&nbsp;&nbsp;&nbsp;</label> <textarea cols="40" rows="3"name="name7" value="" /><br />
</td>
 </tr>
 <tr >
 <td >
</td>
 </tr>
  <tr >
 <td >
</td>
 </tr>
 


 
</table>


<table cellspacing="0" cellpadding="0" border="0" >
         <tr>
           <td colspan="2"  >
        </td>
        </tr>
         <tr>
           <td colspan="2"   >
        </td>
        </tr>
         <tr>
           <td colspan="1" >
            </td>
             <td colspan="1" style="text-align: right" >
             <p style="font-size: 10px;">Signature du récepteur</p>
            </td>
        </tr>
</table>


 </form>
 ';




        $pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'none', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
        $pdf->writeHTMLCell(200, 30, 0, 0,  '<img src="/photo/entetefichefl.png">',0, 1, 0 );
        $pdf->writeHTMLCell(150,0,30,30,$tbl);






        $html = <<<EOF

EOF;

// output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
        $pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
        $namefl='';
        $namefl="ficheliaison".$referencefl.".pdf";
        //;

        $pdf->Output($namefl, 'D');

    }








}