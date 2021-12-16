<?php

namespace App\Controller;

use App\Entity\Datepvinterne;
use App\Entity\Pvinternes;
use App\Entity\SearchDatePv;
use App\Entity\Searchpv;
use App\Form\Pvinterneform;
use App\Form\PvinternesType;
use App\Form\PvrType;
use App\Entity\SearchProjetPv;
use App\Form\SearchDatePvinterne;
use App\Form\SearchpvType;
use App\Repository\DatepvinterneRepository;
use App\Repository\PvinternesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

/**
 * @Route("/pvinternes")
 */
class PvinternesController extends AbstractController
{

    /**
     * @Route("/", name="pvinternes_index",methods={"GET"})
     */
    public function index(DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        $data=new SearchDatePv();
        $form=$this->createForm(SearchDatePvinterne::class,$data);
        $form->handleRequest($request);
        $datepvs=$datepvinterneRepository->searchbilanmensuelfournisseur($data);
        dump($datepvs);
        return $this->render('pvinternes/index.html.twig', [
            'datepvs'=>$datepvs,

            'form'=>$form->createView(),
        ]);


    }

    /**
     * @Route("/date/{datepvinterne}/{month}/{year}", name="pvinternesdate",methods={"GET"})
     */
    public function indexdate(PvinternesRepository $pvinternesRepository,Datepvinterne $datepvinterne,DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        $data=new Searchpv();
        $form=$this->createForm(SearchpvType::class,$data);
        $form->handleRequest($request);
        $pvs=$pvinternesRepository->findSearchpv($data,$datepvinterne->getId());
        return $this->render('pvinternes/pvdate.html.twig', [
            'datepv'=>$datepvinterne,
            'pvs'=>$pvs,
            'form'=>$form->createView(),
        ]);


    }

    /**
     * @Route("/modification/{pvinternes}/{id}", name="modifypv",methods={"GET","POST"})
     */
    public function modify(NotifierInterface $notifier, PvinternesRepository $pvinternesRepository,Datepvinterne $datepvinterne, Pvinternes $pvinternes,DatepvinterneRepository $datepvinterneRepository,Request $request)
    { $maxpvs=$pvinternesRepository->maxpv($pvinternes->getProjet()->getId());
       $pourcentagepv = array();
       foreach ($maxpvs as$po){
           array_push($pourcentagepv,$po->getPourcentage());
       }
       if(sizeof($pourcentagepv,COUNT_NORMAL)==0){
           $maxpv=0;
       }
       else {
           $maxpv = max($pourcentagepv);
       }
        $form = $this->createForm(Pvinterneform::class, $pvinternes);
        $form->handleRequest($request);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {

            $type = $request->request->get('type');
            $taux = $request->request->get('name');
            if ($type == 1) { //enregistrer
                if ($taux > 100) {
                    return new JsonResponse(array( //cas succes
                        'status' => 'OK',
                        'message' => 'le pourcentage ne peut pas être supérieure à 100%',
                        'success' => false,
                        // 'redirect' => $this->generateUrl('])
                    ),
                        200);
                } else {
                    if (($taux <= $maxpv)&&($taux!=0)) {
                        return new JsonResponse(array( //cas succes
                            'status' => 'OK',
                            'message' => 'le pourcentage ne peut pas inférieure à ce qui a déjà été débité',
                            'gfyvuzq'=>$taux,
                            'success' => false,
                            // 'redirect' => $this->generateUrl('])
                        ),
                            200);
                    } else {
                        if ($taux < 0) {
                            return new JsonResponse(array( //cas succes
                                'status' => 'OK',
                                'message' => 'le pourcentage ne peut pas être négatif',
                                'success' => false,
                                // 'redirect' => $this->generateUrl('])
                            ),
                                200);
                        } else {
                            $pvinternes->setPourcentage($taux);
                            $this->getDoctrine()->getManager()->flush();
                            $notifier->send(new Notification('Le PVR interne a bien été modifié', ['browser']));
                            return new JsonResponse(array( //cas succes
                                'status' => 'OK',
                                'message' => 'le PVR interne a bien été modifié',
                                'success' => true,
                                'redirect' => $this->generateUrl('modifypv', ['pvinternes'=>$pvinternes->getId(),'id'=>$pvinternes->getDate()->getId() ])
                            ),
                                200);

                        }
                    }
                }


            }
            else{
                if ($taux > 100) {
                    return new JsonResponse(array( //cas succes
                        'status' => 'OK',
                        'message' => 'le pourcentage ne peut pas être supérieure à 100%',
                        'success' => false,
                        // 'redirect' => $this->generateUrl('])
                    ),
                        200);
                } else {
                    if (($taux <= $maxpv)&&($taux!=0)) {
                        return new JsonResponse(array( //cas succes
                            'status' => 'OK',
                            'message' => 'le pourcentage ne peut pas inférieure à ce qui a déjà été débité',
                            'gfyvuzq'=>$taux,
                            'success' => false,
                            // 'redirect' => $this->generateUrl('])
                        ),
                            200);
                    } else {
                        if ($taux < 0) {
                            return new JsonResponse(array( //cas succes
                                'status' => 'OK',
                                'message' => 'le pourcentage ne peut pas être négatif',
                                'success' => false,
                                // 'redirect' => $this->generateUrl('])
                            ),
                                200);
                        } else {
                            $pvinternes->setPourcentage($taux);
                            $pvinternes->setIsvalidate(true);
                            $pvinternes->setDatefin(new \DateTime());
                            $mymonth=date_format($datepvinterne->getDatemy(),'m');
                            $myyear=date_format($datepvinterne->getDatemy(),'Y');
                            if ($mymonth == 12) {
                                $myyear = $myyear + 1;
                                $mymonth = 1;
                            } else {
                                $mymonth = $mymonth + 1;
                            }
                            $sched = new \DateTime();
                            $sched->setDate($myyear, $mymonth, 1);
                            $existpv=$datepvinterneRepository->owndatepv($mymonth, $myyear);
                            if($taux<100) {
                                if ($existpv) { //on cree un pv interne avec date= pv interne pass

                                    $pvinterne = new Pvinternes();
                                    $pvinterne->setProjet($pvinternes->getProjet());
                                    $pvinterne->setDate($existpv);
                                    $pvinterne->setDatedebut(new \DateTime());
                                    $pvinterne->setIsmodified(false);
                                    $pvinterne->setIsvalidate(false);
                                    $pvinterne->setPourcentage(0);
                                    $this->getDoctrine()->getManager()->persist($pvinterne);
                                    $this->getDoctrine()->getManager()->flush();

                                } else { // on cree tout

                                    $datepvinterne = new Datepvinterne();
                                    $datepvinterne->setDatemy($sched);
                                    $pvinterne = new Pvinternes();
                                    $pvinterne->setDatedebut(new \DateTime());
                                    $pvinterne->setProjet($pvinternes->getProjet());
                                    $pvinterne->setDate($datepvinterne);
                                    $pvinterne->setIsmodified(false);
                                    $pvinterne->setIsvalidate(false);
                                    $pvinterne->setPourcentage(0);
                                    $this->getDoctrine()->getManager()->persist($datepvinterne);
                                    $this->getDoctrine()->getManager()->persist($pvinterne);
                                    $this->getDoctrine()->getManager()->flush();

                                }
                            }
                            $this->getDoctrine()->getManager()->flush();
                            $notifier->send(new Notification('Le PVR interne a bien été validé', ['browser']));

                            return new JsonResponse(array( //cas succes
                                'status' => 'OK',
                                'message' => 'le PVR interne',
                                'success' => true,
                                'redirect' => $this->generateUrl('pvrinternes_pvr', [ 'id'=> $pvinternes->getId()]),
                            ),
                                200);

                        }
                    }
                }
            }
        }








        return $this->render('pvinternes/modify.html.twig', [
            'datepv'=>$datepvinterne,
            'pv'=>$pvinternes,
            'maxp'=>$maxpv,
            'form'=>$form->createView(),

        ]);


    }

    /**
     * @Route("/pvinterne/{pvinternes}/{id}", name="showpv",methods={"GET","POST"})
     */
    public function show(NotifierInterface $notifier, PvinternesRepository $pvinternesRepository,Datepvinterne $datepvinterne, Pvinternes $pvinternes,DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        return $this->render('pvinternes/show.html.twig', [
            'datepv'=>$datepvinterne,
            'pv'=>$pvinternes,

        ]);


    }



    /**
     * @Route("/{id}/pvr", name="pvrinternes_pvr",methods={"GET","POST"})
     */
    public function pvr(Request $request, Pvinternes $pvinternes): Response
    {
        $form = $this->createForm(PvrType::class, $pvinternes);
        $form->get('signataire')->setData($this->getUser()->getUsername());
        $form->get('datesignature')->setData(new \DateTime());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $objet= $form->get('objet')->getNormData();
            $refpv=$form->get('refpv')->getNormData();
            $datepv = $form->get('datepv')->getViewData();
            $datepv=date("d/m/Y",strtotime($datepv));
            $refcontrat=$form->get('refcontrat')->getNormData();
            $facture=$form->get('facture')->getNormData();
            $refcontratsap=$form->get('refcontratsap')->getNormData();
            $boncommande=$form->get('boncommande')->getNormData();

            $datedebut = $form->get('datedebut')->getViewData();
            $datedebut=date("d/m/Y",strtotime($datedebut));
            $datefin = $form->get('datefin')->getViewData();
            $datefin=date("d/m/Y",strtotime($datefin));

            $reservemineure=$form->get('reservemineure')->getViewData();
            $reservemajeure=$form->get('reservemajeure')->getViewData();
            $conditions=$form->get('conditions')->getViewData();
            $pourcentage=$form->get('pourcentage')->getViewData();
            $nomdesignation=$form->get('nomdesignation')->getViewData();
            $qttdesignation=$form->get('qttdesignation')->getViewData();
            $nomdesignation2=$form->get('nomdesignation2')->getViewData();
            $qttdesignation2=$form->get('qttdesignation2')->getViewData();

            $bonapayer=$form->get('bonapayer')->getViewData();

            $signataire=$form->get('signataire')->getViewData();
            $rolesignataire=$form->get('rolesignataire')->getViewData();
            $datesignature = $form->get('datesignature')->getViewData();
            $datesignature=date("d/m/Y",strtotime($datesignature));
            $fournisseur=$pvinternes->getProjet()->getFournisseur()->getName();
            $nameof = $pvinternes->getProjet()->getReference();
            try {
                $this->createpvr($nameof,$objet,$refpv,$datepv,$refcontrat,$facture,$refcontratsap,$boncommande,$datedebut,$datefin,$reservemineure,$reservemajeure,$conditions,$pourcentage,$nomdesignation, $qttdesignation, $nomdesignation2, $qttdesignation2,$bonapayer, $signataire, $rolesignataire,  $datesignature, $fournisseur);
            }
            catch (IOException $exception){}

        }
        return $this->renderForm('pvinternes/pvr.html.twig', [
            'pvinternes'=>$pvinternes,
            'projet'=>$pvinternes->getProjet(),
            'form' => $form,
        ]);
    }



    function createpvr($nameof,$objet,$refpv,$datepv,$refcontrat,$facture,$refcontratsap,$boncommande,$datedebut,$datefin,$reservemineure,$reservemajeure,$conditions,$pourcentage,$nomdesignation, $qttdesignation, $nomdesignation2, $qttdesignation2,$bonapayer, $signataire, $rolesignataire, $datesignature, $fournisseur)
{

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


        $tbf ='';
        $tbf='
    <div style="width: 100%;border-bottom: black 2px solid">
   <table cellspacing="0" cellpadding="0" border="0">
    
     <tr>
       <td style="font-weight: bold;font-size:12vh">'.$fournisseur.'</td>
        <td style="font-size:12vh">Facture : ' .$facture.'</td>
    </tr>
     <tr>
        <td style="font-size:12vh">Objet : '.$objet.'</td>
        <td style="font-size:12vh">Réf. Contrat Cadre SAP : ' .$refcontratsap.'</td>
    </tr>
     <tr>
        <td style="font-size:12vh">Référence PV : '.$refpv.'</td>
        <td style="font-size:12vh">Bon de commande : '.$boncommande.'</td>
    </tr>
    <tr>
        <td style="font-size:12vh">Date PV : '.$datepv.'</td>
        <td style="font-size:12vh">Période du : ' .$datedebut.'</td>
    </tr>
     <tr>
        <td style="font-size:12vh">Réf. Contrat : '.$refcontrat.'</td>
        <td style="font-size:12vh">Au : '.$datefin.'</td>
    </tr>

</table>
</div>
<div style="width: 100%;border-bottom: black 2px solid">
<div style="font-size: 11vh">
Les services/équipements relatives aux contrat/BC/Facture/Période cités ci-dessus  ont été bien réalisés selon les termes du bon de commande et/ou dispositions contractuelles convenus entre <span style="font-weight: bold">Orange Maroc</span> et <span style="font-weight: bold">'.$fournisseur.' </span></div>

<div style="font-size: 11vh">Ce PV a été dressé aux fins de preuve et de confirmation de la bonne réalisation/livraison/installation des dites prestations/équipements sous réserves des remarques ci-dessous
</div>
<div style="font-size: 12vh">
<span style="font-weight: bold;text-decoration-line: underline">
Réserves :
</span>
</div>';
        if ($reservemajeure!=null){
            $tbf.='
    <div style="font-size: 12vh">
       '.$reservemajeure.'
</div>';
        }
        else
        {$tbf.='
        <div style="font-size: 12vh">- RAS</div>
';
        }

    if ($reservemineure!=null){
        $tbf.='
    <div style="font-size: 12vh">
       '.$reservemineure.'
</div>';
    }
    else
    {$tbf.='
        <div style="font-size: 12vh">- RAS</div>
';
    }

        $tbf.='
<div style="font-size: 12vh">
<span style="font-weight: bold;text-decoration-line: underline">
Dispositions contractuelles Sujettes de Validation :
</span>
</div>
<table cellspacing="0" cellpadding="0" border="1">
    
     <tr>
       <td style="font-size:12vh; text-align: center">Désignation</td>
       <td style="font-size:12vh;text-align: center">Quantité livrée</td>
        
    </tr>
     <tr>
        <td style="font-size:12vh;text-align: center">'.$conditions.'</td>
        <td style="font-size:12vh;text-align: center">'.$pourcentage.'</td>
       
    </tr>
     <tr>
         <td style="font-size:12vh;text-align: center">'.$nomdesignation.'</td>
        <td style="font-size:12vh;text-align: center">'.$qttdesignation.'</td>
    </tr>
         <tr>
         <td style="font-size:12vh;text-align: center">'.$nomdesignation2.'</td>
        <td style="font-size:12vh;text-align: center">'.$qttdesignation2.'</td>
    </tr>
    </table>
</div>
<div style="width: 100%;border-bottom: black 2px solid">
    <div style="font-size: 12vh">
<span style="font-weight: bold;text-decoration-line: underline">
Pénalités :
</span>
<span style="font-size: 10vh;text-decoration-line: underline">
(Mention  Obligatoire)</span><br> <span font-size: 10vh>Est-ce que ce document constitue un « Bon à Payer »?  </span>                  
';
        if ($bonapayer==0) {
            $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Oui</label>
                <input type="checkbox" name="agree" value="1" checked="checked" disabled="disabled" readonly="readonly"/> <label for="agree">Non</label>
   </div>             
'; }

        else  {  $tbf.='
                <input type="checkbox" name="agree" value="1" checked="checked" disabled="disabled" readonly="readonly"/> <label for="agree">Oui</label>
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree">Non</label>               
</div>
';}

        $tbf.='

        
   
    <br>
    </div>
 <div style="width: 100%;border-bottom: black 2px solid">
<form method="post" action="http://localhost/printvars.php" >
 <table cellspacing="0" cellpadding="0" border="1">
    
     <tr>
       <td style="font-size:12vh; text-align: center"></td>
       <td style="font-size:12vh;text-align: center;font-weight: bold">Orange Maroc</td>
       <td style="font-size:12vh;text-align: center;font-weight: bold">'.$fournisseur.'</td>
    </tr>
    <tr>
       <td style="font-size:12vh; text-align: center">Nom du signataire</td>
       <td style="font-size:12vh;text-align: center">'.$signataire.'</td>
       <td style="font-size:12vh;text-align: left"><input  type="text" name="name" value="" size="15"  maxlength="50" /></td>
    </tr>
    <tr>
       <td style="font-size:12vh; text-align: center">Qualité du signataire</td>
       <td style="font-size:12vh;text-align: center">'.$rolesignataire.'</td>
       <td style="font-size:12vh;text-align: left"><input type="text" name="nameb" value="" size="15" maxlength="50" /></td>
    </tr>
     <tr>
       <td style="font-size:12vh; text-align: center">Date</td>
       <td style="font-size:12vh;text-align: center">'.$datesignature.'</td>
       <td style="font-size:12vh;text-align: left"><input type="text" name="namec" value="" size="15" maxlength="20"/></td>
    </tr> 
    
    <tr >
       <td style="font-size:12vh; text-align: center">Cachet et signature</td>
       <td style="font-size:12vh;text-align: center"><br><br><br></td>
       <td style="font-size:12vh;text-align: center"><br><br><br></td>
    </tr>   
    
 </table>
 </form>
 </div> 

    
    
    
    
    
    
    
    
    
    ';












        //$pdf->WriteHTMLCell(200, 10,0,0,'', 'B', 'C', 0, 0);
        $pdf->writeHTMLCell(200, 20,5 , 0,  '<img src="/photo/pvrentete.PNG">','B', 1, 0 );
        $pdf->writeHTMLCell(200,0,5,50,$tbf,0,1,0);





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
        $namefl="PVR_interne".$nameof.".pdf";
        //;
        $pdf->Output($namefl, 'D');
    }











}



