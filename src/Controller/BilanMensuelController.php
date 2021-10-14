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
use App\Repository\FournisseurRepository;
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
}








