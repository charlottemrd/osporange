<?php

namespace App\Controller;
use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
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
#[Route('/bilanmensuel')]
class BilanMensuelController extends AbstractController
{


    #[Route('/', name: 'bilanmensuel_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository, FournisseurRepository $fournisseurRepository,Request $request)
    {
        $projets = $fournisseurRepository->searchbilanfournisseur();
        return $this->render('bilanmensuel/index.html.twig', [
            'fournisseurs'=>$projets,
        ]);
    }

    #[Route('/{name}', name: 'bilanmensuel_fournisseur', methods: ['GET'])]
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





    #[Route('/{name}/{idmonthbm}/{month}/{year}', name: 'bilanmensuel_fournisseurmois', methods: ['GET','POST'])]
    public function fournisseurmois(NotifierInterface $notifier, CoutRepository $coutRepository, InfobilanRepository $infobilanRepository, ProjetRepository $projetRepository,  Fournisseur $fournisseur, Idmonthbm $idmonthbm, BilanmensuelRepository $bilanmensuelRepository,IdmonthbmRepository $idmonthbmRepository, ProfilRepository $profilRepository,Request $request)
    {

        $bilans=$bilanmensuelRepository->findBy(array('id'=>$idmonthbm));
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
                        $lignealire = $datatransmis[$iz];
                        $ligneluprof = $profilRepository->findOneBy(array('id' => $lignealire[0]));
                        $lignelunb = ($lignealire[1]);
                        $tariflu = $ligneluprof->getTarif();
                        $coutsoumis = $coutsoumis + ($tariflu * $lignelunb);
                    }
                    $phaseprojet = $project->getPhase()->getId();
                    if ($phaseprojet == 6) {
                        $pourcentagecontrol = 20;
                    } elseif ($phaseprojet == 7) {
                        $pourcentagecontrol = 60;
                    } else if (($phaseprojet == 8) || ($phaseprojet == 9)) {
                        $pourcentagecontrol = 80;
                    } else {
                        $pourcentagecontrol = 100;
                    }
                    $pourcentagesoumis = (100 * ($coutsoumis + $coutdebit)) / ($couttotal);
                    if ($pourcentagesoumis > $pourcentagecontrol) {
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

                                'redirect' => $this->generateUrl('bilanmensuel_fournisseurmois', ['idmonthbm' => $idmonthbm->getId(), 'name' => $fournisseur->getName(), 'month' => $mymonth, 'year' => $myyear])
                            ),
                                200);
                        }

                    }


                }// fin cas où on modifie le bilan mensuel
                else {  //type=2 ; on souhaite valide le bilan mensuel
                    $idmonthbm->setIsaccept(true);
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


}








