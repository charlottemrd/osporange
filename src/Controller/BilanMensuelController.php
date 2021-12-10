<?php

namespace App\Controller;
use App\Entity\Bilanmensuel;
use App\Service\Monthleft;
use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
use App\Entity\Infobilan;
use App\Entity\Profil;
use App\Entity\Projet;
use App\Entity\SearchBilanmensuel;
use App\Form\IdmonthbmType;
use App\Form\SearchBilanType;
use App\Repository\BilanmensuelRepository;
use App\Repository\CoutRepository;
use App\Repository\FournisseurRepository;
use App\Repository\IdmonthbmRepository;
use App\Repository\InfobilanRepository;
use App\Repository\ProjetRepository;
use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Notifier\Notification\Notification;
use DateInterval;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
/**
 * @Route("/bilanmensuel")
 */
class BilanMensuelController extends AbstractController
{


    /**
     * @Route("/", name="bilanmensuel_index",methods={"GET"})
     */
    public function index(ProjetRepository $projetRepository, FournisseurRepository $fournisseurRepository,Request $request)
    {
        $user = $this->getUser();
        $projets = $fournisseurRepository->searchbilanfournisseur($user->getUsername() );
        return $this->render('bilanmensuel/index.html.twig', [
            'fournisseurs'=>$projets,
        ]);
    }

    /**
     * @Route("/{name}", name="bilanmensuel_fournisseur",methods={"GET","POST"})
     * @Entity("comment", expr="repository.find(name)")
     */
    public function fournisseur(Fournisseur $fournisseur, IdmonthbmRepository $idmonthbmRepository, Request $request)
    {
        $data=new SearchBilanmensuel();
        $form=$this->createForm(SearchBilanType::class,$data);
        $form->handleRequest($request);
        $idmonthbms= $idmonthbmRepository->searchbilanmensuelfournisseur($data,$fournisseur);
       return $this->render('bilanmensuel/bilanmensuelfournisseur.htlm.twig', [
            'bilans'=>$idmonthbms,
            'fournisseur'=>$fournisseur,
            'form'=>$form->createView(),
        ]);
    }






    /**
     * @Route("/{name}/{idmonthbm}/{month}/{year}", name="bilanmensuel_fournisseurmois",methods={"GET","POST"})
     * @Entity("comment", expr="repository.find(name)")
     */

    public function fournisseurmois(Monthleft $monthleft, NotifierInterface $notifier, CoutRepository $coutRepository, InfobilanRepository $infobilanRepository, ProjetRepository $projetRepository,  Fournisseur $fournisseur, Idmonthbm $idmonthbm, BilanmensuelRepository $bilanmensuelRepository,IdmonthbmRepository $idmonthbmRepository, ProfilRepository $profilRepository,Request $request)
    {

        $bilans=$bilanmensuelRepository->findBy(array('idmonthbm'=>$idmonthbm));
        $profils=$profilRepository->findProfils($fournisseur);

        $form = $this->createForm(IdmonthbmType::class,$idmonthbm);

        $mybilan=$idmonthbm;
        $myyearmonth=$mybilan->getMonthyear();
        $mymonth=date_format($myyearmonth, 'm');
        $myyear=date_format($myyearmonth, 'Y');

        $form->handleRequest($request);

            if ($request->isXmlHttpRequest()) {

                $type = $request->request->get('type');
                if ($type == 1) {
                    $namemodif = $request->request->get('name');
                    $project = $projetRepository->findOneBy(array('reference' => $namemodif));
                    $couttotal = 0;
                    $thebilan = $bilanmensuelRepository->searchlebilanmensuel($idmonthbm->getId(), $project->getId());
                    $profils = $project->getCouts();
                    foreach ($profils as $pa) {// calcule cout total projet
                        $idpa = $pa->getProfil();
                        $pm = $profilRepository->findOneBy(array('id' => $idpa))->getTarif();
                        $pd = $pa->getNombreprofil();
                        $couttotal = $couttotal + ($pm * $pd);
                    }

                    //calcul du cout debite du projet

                    $anciensbilans = $infobilanRepository->searchinfobilandebite($project->getId());

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
                    }//end else

                    // $taskinfobilan = new Bilanmensuel(); //bilanmensuel
                    $datatransmis = $request->request->get('listedata');
                    $tailledata = sizeof($datatransmis, COUNT_NORMAL);
                    $coutsoumis = 0;
                    for ($iz = 0; $iz < $tailledata; $iz++) {
                        $lignelunb = 0;
                        $lignealire = $datatransmis[$iz];
                        $ligneluprof = $profilRepository->findOneBy(array('id' => $lignealire[0]));
                        $lignelunb = ($lignealire[1]);
                        $tariflu = $ligneluprof->getTarif();
                        $coutsoumis = $coutsoumis + ($tariflu * $lignelunb);
                    }
                    $phaseprojet = $project->getPhase()->getId();
                    if ($phaseprojet == 6) {
                        if ($project->getDebit1bm() != null) {
                            $pourcentagecontrol = $project->getDebit1bm();
                        } else {
                            $pourcentagecontrol = 20;
                        }
                    } elseif ($phaseprojet == 7) {
                        if ($project->getDebit2bm() != null) {
                            $pourcentagecontrol = $project->getDebit2bm();
                        } else {
                            $pourcentagecontrol = 60;
                        }
                    } else if (($phaseprojet == 8) || ($phaseprojet == 9)) {
                        if ($project->getDebit3bm() != null) {
                            $pourcentagecontrol = $project->getDebit3bm();
                        } else {
                            $pourcentagecontrol = 80;
                        }
                    } else {
                        $pourcentagecontrol = 100;
                    }
                    if ($couttotal == 0) {
                        $pourcentagesoumis = 0;
                    } else {
                        $pourcentagesoumis = (100 * ($coutsoumis + $coutdebit)) / ($couttotal);
                    }
                    if (($pourcentagesoumis > $pourcentagecontrol) && ($coutsoumis != 0)) {
                        $message0 = 'Impossible de modifier le bilan mensuel ; le pourcentage débité est supérieure à ' . strval($pourcentagecontrol) . ' %';
                        //problem
                        return new JsonResponse(array( //cas projet est superieure à ce qui est demande
                            'status' => 'OK',
                            'message' => $message0,
                            'success' => false,
                            'zsa' => $pourcentagesoumis,
                        ),
                            200);
                    } else { //deuxieme controle yes
                        $resulttoreturn = false;
                        for ($iza = 0; $iza < $tailledata; $iza++) { //on lit tout les profils
                            $lignealirea = $datatransmis[$iza];
                            $ligneluprofa = $profilRepository->findOneBy(array('id' => $lignealirea[0])); //profil
                            $profilsoumis = ($lignealirea[1]); //nb
                            $ancieninfo = $infobilanRepository->searchinfobilandebiteduprofit($project->getId(), $ligneluprofa->getId()); //anciensinfo
                            $profildebite = 0;
                            foreach ($ancieninfo as $au) {
                                $profildebite = $profildebite + $au->getNombreprofit();
                            }
                            $profitotale = $coutRepository->searchcoutbm($project->getId(), $ligneluprofa->getId());
                            $nbtotalprofit = $profitotale->getNombreprofil();
                            if ($nbtotalprofit - $profildebite - $profilsoumis < 0) {
                                $resulttoreturn = true;
                                $message0 = 'Impossible de modifier le bilan mensuel pour le projet ' . $project->getReference() . ' ; le profil ' . $ligneluprofa->getName() . ' dépasse le quota autorisé';
                                break;
                            }


                        }
                        if ($resulttoreturn) {

                            return new JsonResponse(array( //cas echec condition 2
                                'status' => 'OK',
                                'message' => $message0,
                                'success' => false,
                                'sz' => $couttotal,
                                'coutdebit' => $coutdebit,
                                'idprojet' => $pourcentagesoumis,


                            ),
                                200);

                        } else { //a soumettre

                            $thebilan->setHavebeenmodified(true);
                            $thebilan->setDatemaj(new \DateTime());
                            $profthebilan = $thebilan->getInfobilans();
                            for ($izm = 0; $izm < $tailledata; $izm++) {
                                $lignealire = $datatransmis[$izm];
                                $numberidofprofil = $profilRepository->findOneBy(array('id' => $lignealire[0]));


                                $theprofittochange = $infobilanRepository->findOneBy(array('bilanmensuel' => $thebilan, 'profil' => $numberidofprofil));
                                $theprofittochange->setNombreprofit($lignealire[1]);
                            }

                            $this->getDoctrine()->getManager()->flush();
                            $notifier->send(new Notification('Le bilan mensuel a bien été modifié', ['browser']));
                            return new JsonResponse(array( //cas succes
                                'status' => 'OK',
                                'message' => 'le bilan mensuel a bien été modifié',
                                'success' => true,
                                'sz' => $couttotal,
                                'coutdebit' => $coutdebit,
                                'idprojet' => $pourcentagesoumis,
                                'sxz' => $pourcentagecontrol,
                                'redirect' => $this->generateUrl('bilanmensuel_fournisseurmois', ['idmonthbm' => $idmonthbm->getId(), 'name' => $fournisseur->getName(), 'month' => $mymonth, 'year' => $myyear])
                            ),
                                200);
                        }


                    }
                }// fin cas où on modifie le bilan mensuel
                else {  //type=2 ; on souhaite valide le bilan mensuel
                    $projets = $idmonthbm->getBilanmensuels();
                    $resultt=false;
                    foreach ($projets as $po) {
                        $pom = $po->getProjet();
                        $resultone = false;


                        $couttotal = $monthleft->coutprojet($po->getProjet());


                        //  $thebilan = $bilanmensuelRepository->searchlebilanmensuel($idmonthbm->getId(), $po->getId());
                        //calcul du cout debite du projet

                        $anciensbilans = $infobilanRepository->searchinfobilandebitefalse($po->getProjet()->getId());

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
                        }//end else


                        $phaseprojet = $po->getProjet()->getPhase()->getId();
                        if ($phaseprojet == 6) {
                            if ($po->getProjet()->getDebit1bm() != null) {
                                $pourcentagecontrol = $po->getProjet()->getDebit1bm();
                            } else {
                                $pourcentagecontrol = 20;
                            }
                        } elseif ($phaseprojet == 7) {
                            if ($po->getProjet()->getDebit2bm() != null) {
                                $pourcentagecontrol = $po->getProjet()->getDebit2bm();
                            } else {
                                $pourcentagecontrol = 60;
                            }
                        } else if (($phaseprojet == 8) || ($phaseprojet == 9)) {
                            if ($po->getProjet()->getDebit3bm() != null) {
                                $pourcentagecontrol = $po->getProjet()->getDebit3bm();
                            } else {
                                $pourcentagecontrol = 80;
                            }
                        } else {
                            $pourcentagecontrol = 100;
                        }
                        if ($couttotal == 0) {
                            $pourcentagesoumis = 0;
                        } else {
                            $pourcentagesoumis = (100 * ($coutdebit)) / ($couttotal);
                        }

                        $coutsoumis = 0;
                        $bm = $bilanmensuelRepository->findOneBy(array('projet' => $po->getProjet(), 'idmonthbm' => $idmonthbm));
                        $if = $bm->getInfobilans();
                        foreach ($if as $ui) {
                            $coutsoumis = $coutsoumis + ($ui->getNombreprofit() * $ui->getProfil()->getTarif());
                        }


                        if (($pourcentagesoumis > $pourcentagecontrol) && ($coutsoumis != 0)) {
                            $resulttwo = true;
                            $message0 = 'Impossible de modifier le bilan mensuel pour le projet '.$po->getProjet()->getReference(). '; le pourcentage débité est supérieure à ' . strval($pourcentagecontrol) . ' %';
                            //problem
                            break;
                        } else { //deuxieme controle yes
                            $resulttwo = false;
                            foreach ($po->getProjet()->getFournisseur()->getProfils() as $prof) { //on lit tout les profils
                                $ancieninfo = $infobilanRepository->searchinfobilandebiteduprofitfalse($po->getProjet()->getId(), $prof->getId()); //anciensinfo
                                $profildebite = 0;
                                foreach ($ancieninfo as $au) {
                                    $profildebite = $profildebite + $au->getNombreprofit();
                                }
                                $profitotale = $coutRepository->searchcoutbm($po->getProjet()->getId(), $prof->getId());
                                $nbtotalprofit = $profitotale->getNombreprofil();
                                if ($nbtotalprofit - $profildebite < 0) {
                                    $resulttwo = true;
                                    $sortie=true;
                                    $resultt=true;
                                    $message0 = 'Impossible de modifier le bilan mensuel pour le projet ' . $po->getProjet()->getReference() . ' ; le profil ' . $prof->getName() . ' dépasse le quota autorisé';
                                    break;
                                }
                                if($resulttwo){
                                    $resultt=true;
                                    break;
                                }


                            }
                          //  if (($resultone) || ($resulttwo)) {
                            //    break;
                           // }
                        }

                    }//foreach projet

                    if ($resultone == true) {

                        return new JsonResponse(array( //cas projet est superieure à ce qui est demande
                            'status' => 'OK',
                            'message' => $message0,
                            'success' => false,
                            'zsa' => $pourcentagesoumis,
                        ));

                    }

                   else if($resultt){
                        return new JsonResponse(array( //cas projet est superieure à ce qui est demande
                            'status' => 'OK',
                            'message' => $message0,
                            'success' => false,
                            'zsa' => $pourcentagesoumis,
                        ));

                    }


                    else {


                        $idmonthbm->setIsaccept(true);
                        $mymonth = date_format($myyearmonth, 'm');
                        $myyear = date_format($myyearmonth, 'Y');

                        if ($mymonth == 12) {
                            $myyear = $myyear + 1;
                            $mymonth = 1;
                        } else {
                            $mymonth = $mymonth + 1;
                        }
                        $sched = new \DateTime();
                        $sched->setDate($myyear, $mymonth, 1);
                        $existbilan = $idmonthbmRepository->ownmonthfournisseur($mymonth, $myyear, $mybilan->getFournisseur()->getId());
                        $bilanajouter = array();
                        $coutdebit = 0;
                        foreach ($bilans as $bilansf) { //pour chaque projet du bilan

                            $anciensbilansdebduprojet = $infobilanRepository->searchinfobilandebite($bilansf->getProjet()->getId());
                            if (sizeof($anciensbilansdebduprojet, COUNT_NORMAL) == 0) {
                                $coutdebit = 0;
                            } else {
                                foreach ($anciensbilansdebduprojet as $anciensbilansdebverif) {  //projet de
                                    $nbz = $anciensbilansdebverif->getNombreprofit();
                                    $profittverif = $anciensbilansdebverif->getProfil();
                                    $pmdz = $profilRepository->findOneBy(array('id' => $profittverif))->getTarif();
                                    $coutdebit = $coutdebit + ($nbz * $pmdz);
                                }
                            }//end else cout debit

                            //cout total
                            $profilsv = $bilansf->getProjet()->getCouts();
                            $couttotal = 0;
                            foreach ($profilsv as $pav) {// calcule cout total projet
                                $idpa = $pav->getProfil();
                                $pma = $profilRepository->findOneBy(array('id' => $idpa))->getTarif();
                                $pda = $pav->getNombreprofil();
                                $couttotal = $couttotal + ($pma * $pda);
                            }

                            //cout bilan en cours

                            $coutencours = 0;
                            foreach ($bilansf->getInfobilans() as $info) {
                                $pr = $info->getProfil()->getId();
                                $tarif = $profilRepository->findOneBy(array('id' => $pr))->getTarif();
                                $coutencours = $coutencours + (($info->getNombreprofit()) * ($tarif));
                            }
                            if ($couttotal != 0) {
                                if ($couttotal - $coutencours - $coutdebit == 0) {
                                    $bilansf->getProjet()->setIseligibletobm(false);
                                    $bilansf->getProjet()->setIsfinish(true);
                                } else {
                                    array_push($bilanajouter, $bilansf->getProjet()->getId());
                                }
                            } else {
                                array_push($bilanajouter, $bilansf->getProjet()->getId());
                            }


                        }//end foreach


                        if ($existbilan) {//on reprend ce bilan

                            for ($a = 0; $a < sizeof($bilanajouter, COUNT_NORMAL); $a++) {
                                $bilanajout = new Bilanmensuel();
                                $mybilan = $bilanajout->getId();
                                $pk = $projetRepository->findOneBy(array('id' => $bilanajouter[$a]));
                                $bilanajout->setProjet($pk);
                                $bilanajout->setIdmonthbm($existbilan);
                                $bilanajout->setDatemaj(new \DateTime());
                                $bilanajout->setHavebeenmodified(false);
                                $this->getDoctrine()->getManager()->persist($bilanajout);

                                foreach ($pk->getFournisseur()->getProfils() as $ps) {
                                    $infoajout = new Infobilan();
                                    $infoajout->setBilanmensuel($bilanajout);
                                    $infoajout->setProfil($ps);
                                    //   $pcom=whichpoc($pk);

                                    $mth = $monthleft->manymonthleft($pk, $existbilan);
                                    $sxz = $monthleft->proposeTGIM($infobilanRepository, $coutRepository, $existbilan, $ps, $pk, $mth);


                                    //  $sxz= proposeTGIM($couttotal, $coutdebit,$pcom, $pk->getFournisseur(),$ps->getTarif());
                                    $infoajout->setNombreprofit($sxz);  //to change with an function
                                    $this->getDoctrine()->getManager()->persist($infoajout);
                                }
                            }


                        } else { //on cree un nouveau bilan

                            $newonebilan = new Idmonthbm();
                            $newonebilan->setMonthyear($sched);
                            $newonebilan->setFournisseur($fournisseur);
                            $newonebilan->setIsaccept(false);
                            $this->getDoctrine()->getManager()->persist($newonebilan);

                            for ($af = 0; $af < sizeof($bilanajouter, COUNT_NORMAL); $af++) {
                                $bilanajoutt = new Bilanmensuel();
                                $mybilan = $bilanajoutt->getId();
                                $pkt = $projetRepository->findOneBy(array('id' => $bilanajouter[$af]));
                                $bilanajoutt->setProjet($pkt);
                                $bilanajoutt->setIdmonthbm($newonebilan);
                                $bilanajoutt->setDatemaj(new \DateTime());
                                $bilanajoutt->setHavebeenmodified(false);
                                $this->getDoctrine()->getManager()->persist($bilanajoutt);

                                foreach ($pkt->getFournisseur()->getProfils() as $pst) {
                                    $infoajoutt = new Infobilan();
                                    $infoajoutt->setBilanmensuel($bilanajoutt);
                                    $infoajoutt->setProfil($pst);
                                    // $pcomt=whichpoc($pkt);
                                    $mtht = $monthleft->manymonthleft($pkt, $newonebilan);
                                    $sxz = $monthleft->proposeTGIM($infobilanRepository, $coutRepository, $newonebilan, $pst, $pkt, $mtht);


                                    $infoajoutt->setNombreprofit($sxz);  //to change with an function
                                    //$infoajoutt->setNombreprofit(0);  //to change with an function
                                    $this->getDoctrine()->getManager()->persist($infoajoutt);
                                }
                            }

                        }

                        // $idmonthbm->setMonthyear($sched);

                        $this->getDoctrine()->getManager()->flush();


                        $notifier->send(new Notification('Le bilan mensuel a bien été accepté', ['browser']));
                        return new JsonResponse(array( //cas succes
                            'status' => 'OK',
                            'message' => 'le bilan mensuel a bien été modifié',
                            'success' => true,

                            'redirect' => $this->generateUrl('bilanmensuel_fournisseur', ['name' => $fournisseur->getName()])
                        ),
                            200);
                    }// cas ou type =2
                    }

                }



       /*











            return $this->redirectToRoute(('bilanmensuel_fournisseurmois'), [
                    'name'=> $fournisseur->getName(),
                    'fournisseur'=>$fournisseur,
                    'mbilan'=>$idmonthbm->getId(),
                    'month'=>$mymonth,
                    'year'=>$myyear
                    ]
                , Response::HTTP_SEE_OTHER);
        }*/

        return $this->render('bilanmensuel/monthbm.html.twig', [
            'bilanmensuel'=>$bilans,
            'mbilan'=>$idmonthbm->getId(),
            'profils'=>$profils,
            'fournisseur'=>$fournisseur,
            'name'=> $fournisseur->getName(),
            'bilan'=>$idmonthbm,
            'month'=>$mymonth,
            'year'=>$myyear,
            'form'=>$form->createView(),
        ]);
    }






    /**
     * @Route("/show/{name}/{idmonthbm}/{month}/{year}", name="showbilanmensuel_fournisseurmois",methods={"GET","POST"})
     */
    public function showfournisseurmois(   Fournisseur $fournisseur, Idmonthbm $idmonthbm, BilanmensuelRepository $bilanmensuelRepository, ProfilRepository $profilRepository,Request $request)
    {

        $bilans=$bilanmensuelRepository->findBy(array('idmonthbm'=>$idmonthbm));
        $profils=$profilRepository->findProfils($fournisseur);


        $mybilan=$idmonthbm;
        $myyearmonth=$mybilan->getMonthyear();

        $mymonth=date_format($myyearmonth, 'm');
        $myyear=date_format($myyearmonth, 'Y');



return $this->render('bilanmensuel/showbm.html.twig', [
            'bilanmensuels'=>$bilans,
            'mbilan'=>$idmonthbm->getId(),
            'profils'=>$profils,
            'fournisseur'=>$fournisseur,
            'name'=> $fournisseur->getName(),
            'bilan'=>$idmonthbm,
            'month'=>$mymonth,
            'year'=>$myyear,

        ]);
    }







}




