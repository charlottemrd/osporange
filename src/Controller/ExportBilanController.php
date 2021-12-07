<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
use App\Repository\BilanmensuelRepository;
use App\Repository\InfobilanRepository;
use App\Repository\ProfilRepository;
use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Request;
class ExportBilanController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/export/bilan/{name}/{idmonthbm}/{month}/{year}", name="export_bilan",methods={"GET","POST"})
     */
    public function indexgetData(InfobilanRepository $infobilanRepository,  Fournisseur $fournisseur, Idmonthbm $idmonthbm, BilanmensuelRepository $bilanmensuelRepository, ProfilRepository $profilRepository, Request $request)
    {


        // return $this->redirectToRoute('projet_index', ['fournisseur'=>$fournisseur,'idmon'=>$idmonthbm], Response::HTTP_SEE_OTHER);
        $bilans=$bilanmensuelRepository->findBy(array('idmonthbm'=>$idmonthbm));
        $profils=$fournisseur->getProfils();



        $pf= array();
        foreach ($profils as $p) {
          array_push($pf,$p->getName());
        }

        $project= array();
        foreach ($bilans as $b) {
            array_push($project,$b->getProjet()->getReference());
        }

        $totaux=array();
        foreach ($bilans as $f){
           $inf= $f->getInfobilans();
            foreach ($inf as $info ){
                $myinfoar= array();
                array_push($myinfoar,$info->getNombreprofit());
            }
            array_push($totaux,$myinfoar);
        }








        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Liste_des_projets');

        $sheet->getCell('A1')->setValue('Projets/Profils');

        $writer = new Xlsx($spreadsheet);
        $colname='A';
        $rowname = '1';
         for ($a = 0; $a <= sizeof($pf,COUNT_NORMAL)-1; $a++) {
                 $colname++;
             $sheet->getCell($colname.$rowname)->setValue($pf[$a]);
         }

        $colnameprojet='A';
        for ($ma = 0; $ma < sizeof($project,COUNT_NORMAL); $ma++) {
            $indexpro=$ma+2;
            $sheet->getCell($colnameprojet.$indexpro)->setValue($project[$ma]);
        }

        $ligne=2;
        foreach ($bilans as $lm){
            $lacolonne='B';
            $infos=$lm->getInfobilans();
            foreach ($infos as $ui){
                $mot=$ui->getNombreprofit();
                $sheet->getCell($lacolonne.$ligne)->setValue($mot);
                $lacolonne++;
            }
            $ligne++;
        }

        $colonnedate='B';
        foreach ($profils as $profildate){
            $colonnedate++;
        }
        $lignedate=2;
        foreach ($bilans as $am){
            $date=$am->getDatemaj();
            $sheet->getCell($colonnedate.$lignedate)->setValue($date);
            $cellg = $sheet->getCell($colonnedate.$lignedate);
            $sheet->getCell($colonnedate.$lignedate)->setValue(date('d-m-Y', strtotime( $cellg->getValue() ) ));
            $lignedate++;
        }

        $sheet->getCell($colonnedate.'1')->setValue('Dernière date de mise à jour');

        $colprofil='B';
        $ligneprofit=2;
        foreach ($bilans as $df){
            $ligneprofit++;
        }
        $thefournisseur=$idmonthbm->getFournisseur();
        foreach ($profils as $profilcout){
            $nameprofit=$sheet->getCell($colprofil.'1')->getValue();
            $coutunitaire=$profilRepository->findOneBy(array('name'=>$nameprofit,'fournisseur'=>$thefournisseur));
            $sheet->getCell($colprofil.$ligneprofit)->setValue($coutunitaire->getTarif());
            $colprofil++;
        }


        $lignenbprofit=$ligneprofit+1;
        $colnbpro='B';
        foreach ($profils as $profilcout){
            $nbof=0;
            for ($la = 0; $la < sizeof($project,COUNT_NORMAL); $la++){
                $ap=$la+2;
                $nbof=$nbof+$sheet->getCell($colnbpro.$ap)->getValue();
            }
            $sheet->getCell($colnbpro.$lignenbprofit)->setValue($nbof);
            $colnbpro++;
        }

        $ligneglob=$lignenbprofit+1;
        $colglob='B';
        foreach ($profils as $profilcout){
            $glob=$sheet->getCell($colglob.$lignenbprofit)->getValue()*$sheet->getCell($colglob.$ligneprofit)->getValue();
            $sheet->getCell($colglob.$ligneglob)->setValue($glob);
            $colglob++;
        }

        $lignett=$ligneglob+1;
        $coltt='B';
        $coutt=0;
        foreach ($profils as $profilcout){
            $coutt=$coutt+ $sheet->getCell($coltt.$ligneglob)->getValue();
            $coltt++;
        }
        $sheet->getCell('B'.$lignett)->setValue($coutt);
        $sheet->getCell('A'.$ligneprofit)->setValue('Montant du profil');
        $sheet->getCell('A'.$lignenbprofit)->setValue('Nombre cumule de profils');
        $sheet->getCell('A'.$ligneglob)->setValue('Cout par profil en '. $idmonthbm->getFournisseur()->getDevise());
        $sheet->getCell('A'.$lignett)->setValue('Cout total en '. $idmonthbm->getFournisseur()->getDevise());




















        // Increase row cursor after header write
      //  $sheet->fromArray($this->getData($projetRepository),null, 'A2', true);




        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="Bilanmensuel.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;

    }







        //  return $this->render('export_bilan/index.html.twig', [
       //     'controller_name' => 'ExportBilanController',
     //   ]);

}
