<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ExporthomeController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/exporthome", name="exporthome")
     */
    public function exporthome()
    {
        return $this->render('exporthome/index.html.twig', [
            'controller_name' => 'ExporthomeController',
        ]);
    }

    private function getData(ProjetRepository $projetRepository): array
    {
        /**
         * @var $user User[]
         */
        $user = $this->getUser();
        $projets = $projetRepository->findexportSearch($user);

        foreach ($projets as $projet) {
            $list[] = [
                $projet->getReference(),
                $projet->getDomaine(),
                $projet->getSdomaine(),
                $projet->getDescription(),
                $projet->getTaux(),
                $projet->getIsplanningrespecte(),
                $projet->getDatel1(),
                $projet->getDate0(),
                $projet->getDate1(),
                $projet->getDate2(),
                $projet->getDate3(),
                $projet->getDatecrea(),
                $projet->getDatespec(),
                $projet->getDatemaj(),
                $projet->getPaiement(),
                $projet->getRisque(),
                $projet->getTypebu(),
                $projet->getPriorite(),
                $projet->getPhase(),
                $projet->getFournisseur(),
                $projet->getUser(),

               /* $projet->getDatereel1(),
                $projet->getDatereel2(),
                $projet->getDatereel3(),


                $projet->getCouts(),
                $projet->getModalites(),
                $projet->getDateLones(),
                $projet->getDatereell1(),
                $projet->getDateZeros(),
                $projet->getDatereel0(),
                $projet->getDateOnePluses(),
                $projet->getDateTwos(),
                $projet->getDataTrois(),
                $projet->getCommentaires()*/




            ];
        }
        return $list;
    }

    /**
     * @Route("/export",  name="export")
     */
    public function export(ProjetRepository $projetRepository)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Liste_des_projets');

        $sheet->getCell('A1')->setValue('Reference');
        $sheet->getCell('B1')->setValue('Domaine');
        $sheet->getCell('C1')->setValue('Sous-domaine');
        $sheet->getCell('D1')->setValue('Description');
        $sheet->getCell('E1')->setValue('Taux');
        $sheet->getCell('F1')->setValue('Respect du planning');
        $sheet->getCell('G1')->setValue('Date T-1');
        $sheet->getCell('H1')->setValue('Date T0');
        $sheet->getCell('I1')->setValue('Date T1');
        $sheet->getCell('J1')->setValue('Date T2');
        $sheet->getCell('K1')->setValue('Date T3');
        $sheet->getCell('L1')->setValue('Date de création');
        $sheet->getCell('M1')->setValue('Date de spécifications');
        $sheet->getCell('N1')->setValue('Date de dernière m.a.j');
        $sheet->getCell('O1')->setValue('Type de paiement');
        $sheet->getCell('P1')->setValue('Risque');
        $sheet->getCell('Q1')->setValue('Type de BU');
        $sheet->getCell('R1')->setValue('Priorite');
        $sheet->getCell('S1')->setValue('Phase');
        $sheet->getCell('T1')->setValue('Fournisseur');
        $sheet->getCell('U1')->setValue('Chef de projet');

        // Increase row cursor after header write
        $sheet->fromArray($this->getData($projetRepository),null, 'A2', true);


        $writer = new Xlsx($spreadsheet);

        $columnf = 'F';
        $lastRowf = $sheet->getHighestRow();
        for ($rowf = 2; $rowf <= $lastRowf; $rowf++) {
            $cellf = $sheet->getCell($columnf.$rowf);
            if($cellf->getValue()=='VRAI')
            {

                $sheet->getCell($columnf.$rowf)->setValue('Oui');
            }
            else{
                $sheet->getCell($columnf.$rowf)->setValue('Non');
            }
        }
        $columng = 'G';
        $lastRowg = $sheet->getHighestRow();
        for ($rowg = 2; $rowg <= $lastRowg; $rowg++) {
            $cellg = $sheet->getCell($columng.$rowg);
            if($cellg->getValue()!=null)
            {
                ;
            $sheet->getCell($columng.$rowg)->setValue(date('d-m-Y', strtotime( $cellg->getValue() ) ));
            }
        }
        $columnh = 'H';
        $lastRowh = $sheet->getHighestRow();
        for ($rowh = 2; $rowh <= $lastRowh; $rowh++) {
            $cellh = $sheet->getCell($columnh.$rowh);
            if($cellh->getValue()!=null)
            {
                ;
                $sheet->getCell($columnh.$rowh)->setValue(date('d-m-Y', strtotime( $cellh->getValue() ) ));
            }
        }
        $columni = 'I';
        $lastRowi = $sheet->getHighestRow();
        for ($rowi = 2; $rowi <= $lastRowi; $rowi++) {
            $celli = $sheet->getCell($columni.$rowi);
            if($celli->getValue()!=null)
            {
                ;
                $sheet->getCell($columni.$rowi)->setValue(date('d-m-Y', strtotime( $celli->getValue() ) ));
            }
        }
        $columnj = 'J';
        $lastRowj = $sheet->getHighestRow();
        for ($rowj = 2; $rowj <= $lastRowj; $rowj++) {
            $cellj = $sheet->getCell($columnj.$rowj);
            if($cellj->getValue()!=null)
            {
                ;
                $sheet->getCell($columnj.$rowj)->setValue(date('d-m-Y', strtotime( $cellj->getValue() ) ));
            }
        }
        $columnk = 'K';
        $lastRowk = $sheet->getHighestRow();
        for ($rowk = 2; $rowk <= $lastRowk; $rowk++) {
            $cellk = $sheet->getCell($columnk.$rowk);
            if($cellk->getValue()!=null)
            {
                ;
                $sheet->getCell($columnk.$rowk)->setValue(date('d-m-Y', strtotime( $cellk->getValue() ) ));
            }
        }
        $columnl = 'L';
        $lastRowl = $sheet->getHighestRow();
        for ($rowl = 2; $rowl <= $lastRowl; $rowl++) {
            $celll = $sheet->getCell($columnl.$rowl);
            if($celll->getValue()!=null)
            {
                ;
                $sheet->getCell($columnl.$rowl)->setValue(date('d-m-Y', strtotime( $celll->getValue() ) ));
            }
        }
        $columnm = 'M';
        $lastRowm = $sheet->getHighestRow();
        for ($rowm = 2; $rowm <= $lastRowm; $rowm++) {
            $cellm = $sheet->getCell($columnm.$rowm);
            if($cellm->getValue()!=null)
            {
                ;
                $sheet->getCell($columnm.$rowm)->setValue(date('d-m-Y', strtotime( $cellm->getValue() ) ));
            }
        }
        $columnn = 'N';
        $lastRown = $sheet->getHighestRow();
        for ($rown = 2; $rown <= $lastRown; $rown++) {
            $celln = $sheet->getCell($columnn.$rown);
            if($celln->getValue()!=null)
            {
                ;
                $sheet->getCell($columnn.$rown)->setValue(date('d-m-Y', strtotime( $celln->getValue() ) ));
            }
        }






        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="Liste_des_projets.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;

    }
}