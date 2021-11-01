<?php

namespace App\Controller;
use App\Entity\Bilanmensuel;
use App\Entity\Cout;
use App\Entity\DataTrois;
use App\Entity\DateLone;
use App\Entity\DateOnePlus;
use App\Entity\DateTwo;
use App\Entity\DateZero;
use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
use App\Entity\Profil;
use App\Entity\Projet;
use App\Entity\SearchBilanmensuel;
use App\Form\FicheliaisonType;
use App\Form\IdmonthbmType;
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
use App\Form\SearchBilanType;
use App\Form\SearchType;
use App\Form\PhaseaType;
use App\Form\PhasebType;
use App\Entity\SearchData;
use App\Repository\BilanmensuelRepository;
use App\Repository\FournisseurRepository;
use App\Repository\IdmonthbmRepository;
use App\Repository\ProjetRepository;
use App\Repository\ProfilRepository;
use App\Repository\PhaseRepository;
use FontLib\TrueType\Collection;
use PhpParser\Node\Expr\AssignOp\Mod;
use PhpParser\Node\Expr\Cast\Array_;
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
    public function fournisseur(Fournisseur $fournisseur, IdmonthbmRepository $idmonthbmRepository, FournisseurRepository $fournisseurRepository,Request $request)
    {
        $data=new SearchBilanmensuel();
        $form=$this->createForm(SearchBilanType::class,$data);
        $form->handleRequest($request);
        $bilans= $idmonthbmRepository->searchbilanmensuelfournisseur($data,$fournisseur);
        return $this->render('bilanmensuel/bilanmensuelfournisseur.htlm.twig', [
            'bilans'=>$bilans,
            'fournisseur'=>$fournisseur,
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/{name}/{month}/{year}', name: 'bilanmensuel_fournisseurmois', methods: ['GET','POST'])]
    public function fournisseurmois(ProjetRepository $projetRepository,  Fournisseur $fournisseur, Idmonthbm $idmonthbm, BilanmensuelRepository $bilanmensuelRepository, ProfilRepository $profilRepository,Request $request)
    {
        $bilans=$bilanmensuelRepository->listebilanmensuel($idmonthbm);
        $profils=$profilRepository->findProfils($fournisseur);

        $form = $this->createForm(IdmonthbmType::class, $idmonthbm);

        $mybilan=$idmonthbm;
        $myyearmonth=$mybilan->getMonthyear();
        $mymonth=date_format($myyearmonth, 'm');;
        $myyear=date_format($myyearmonth, 'Y');

        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {

            $type = $request->request->get('type');
            if($type==1) {
                $namemodif = $request->request->get('name');
                $project=$projetRepository->findOneBy(array('reference'=>$namemodif));
                $cout=0;
                $profils=$project->getCouts();
                foreach($profils as $pa){
                    $idpa=$pa->getProfil();
                    $pm=$profilRepository->findOneBy(array('id'=>$idpa))->getTarif();
                    $pd = $pa->getNombreprofil();
                    $cout=$cout+($pm * $pd);
                }
                return new JsonResponse(array( //cas succes
                    'status' => 'OK',
                    'message' => 0,
                    'success'  => true,
                    'sz'=>$cout,
                    'idprojet'=>$project->getReference(),
                    //'redirect' => $this->generateUrl('fournisseur_liste_index',['sz'=>$pm])
                ),
                    200);
            }//endif type==1
            else{  //type=2
                return new JsonResponse(array(
                    'status' => 'OK',
                    'message' => 0),
                    200);
            }// cas ou type =2

        }

        if ($form->isSubmitted()) {
            $type = $form->get('type')->getViewData();
            $namebutton = $form->get('namebutton')->getViewData();
            if ($type!=2){
                return $this->redirectToRoute('projet_index', [
                    'type'=>$type,
                    'namebutton'=>$namebutton
                ], Response::HTTP_SEE_OTHER);
            }
            else{
                // le projet est valide
            }







            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(('bilanmensuel_fournisseurmois'), [
                    'name'=> $fournisseur->getName(),
                    'fournisseur'=>$fournisseur,
                    'bilan'=>$idmonthbm,
                    'month'=>$mymonth,
                    'year'=>$myyear]
                , Response::HTTP_SEE_OTHER);
        }

        return $this->render('bilanmensuel/monthbm.html.twig', [
            'bilanmensuel'=>$bilans,
            'profils'=>$profils,
            'month'=>$mymonth,
            'year'=>$myyear,
            'fournisseur'=>$fournisseur,
            'name'=> $fournisseur->getName(),
            'bilan'=>$idmonthbm,
            'form'=>$form->createView(),
        ]);
    }


}








