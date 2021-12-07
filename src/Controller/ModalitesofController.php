<?php

namespace App\Controller;

use App\Entity\Modalites;
use App\Entity\Projet;
use App\Form\Modalites1Type;
use App\Entity\SearchData;
use App\Repository\ModalitesRepository;
use App\Repository\ProjetRepository;
use App\Form\PacType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\SearchType;
use TCPDF;


/**
 * @Route("/listemodalites")
 */
class ModalitesofController extends AbstractController
{

    /**
     * @Route("/", name="modalitesof_index",methods={"GET","POST"})
     */
    public function index(ModalitesRepository $modalitesRepository, ProjetRepository $projetRepository, Request $request)  //par projet
    {
        $data=new SearchData();
        $form=$this->createForm(SearchType::class,$data);
        $form->handleRequest($request);
        $user = $this->getUser();
        $projets = $projetRepository->findSearchMof($data,$user);
        return $this->render('modalitesof/index.html.twig', [
            'projets' => $projets,
            'form'=>$form->createView()
        ]);
    }



    /**
     * @Route("/{id}", name="modalitesof_show",methods={"GET","POST"})
     */
    public function show(Projet $projet,ModalitesRepository $modalitesRepository,Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $thepourcentage = $request->request->get('pourcentage');
            $themodalite = $modalitesRepository->findOneBy(array('projet' => $projet, 'pourcentage' => $thepourcentage));
            $modalitesnonapprouved = $modalitesRepository->isreadytobeapproved($thepourcentage, $projet);
            if ($modalitesnonapprouved) {

                $message0 ='  La modalité ne peux pas être approuvée, tant que les précédentes n\'ont pas été validé';

                return new JsonResponse(array(
                    'status' => 'OK',
                    'message' => $message0,
                    'success' => false,

                ),
                    200);

            } else {

                $themodalite->setIsapproved(1);
                $themodalite->setDecisionsapproved(1);
                $themodalite->setDatefin(new \DateTime());
                $themodalite->setIsencours(false);
                $modaliteapres=$modalitesRepository->findOneBy(array('projet'=>$projet,'rank'=>$themodalite->getRank()+1));

                if ($modaliteapres){
                    $modaliteapres->setDatedebut(new \DateTime());
                    $modaliteapres->setRank(true);
                }
                $this->getDoctrine()->getManager()->flush();

                $message0 = 'La modalité a bien été approuvé';

                return new JsonResponse(array(
                    'status' => 'OK',
                    'message' => $message0,
                    'success' => true,
                    'redirect' => $this->generateUrl('modalitesof_pac', ['id' => $themodalite->getId()])
                ),
                    200);
            }
        }








        return $this->render('modalitesof/show.html.twig', [
            'projet'=>$projet,
            'modalites'=>$modalitesRepository->findBy(array('projet'=>$projet),array('pourcentage'=>'ASC')),
        ]);
    }




    /**
     * @Route("/{id}/pac", name="modalitesof_pac",methods={"GET","POST"})
     */
    public function pac(Request $request, Modalites $modalites): Response
    {
        $form = $this->createForm(PacType::class, $modalites);
        $form->get('signataire')->setData($this->getUser());
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

            $reserve1=$form->get('reserve1')->getViewData();
            $conditions=$form->get('conditions')->getViewData();
            $pourcentage=$form->get('pourcentage')->getViewData();
            $nomdesignation=$form->get('nomdesignation')->getViewData();
            $qttdesignation=$form->get('qttdesignation')->getViewData();

            $penalites=$form->get('penalites')->getViewData();
            $retard=$form->get('retard')->getViewData();
            $retardmontant=$form->get('retardmontant')->getViewData();
            $respect=$form->get('respect')->getViewData();
            $respectmontant=$form->get('respectmontant')->getViewData();
            $degat=$form->get('degat')->getViewData();
            $degatmontant=$form->get('degatmontant')->getViewData();
            $qualite=$form->get('qualite')->getViewData();
            $qualitemontant=$form->get('qualitemontant')->getViewData();
            $retardfact=$form->get('retardfact')->getViewData();
            $retardfactmontant=$form->get('retardfactmontant')->getViewData();
            $autre=$form->get('autre')->getViewData();
            $autremontant=$form->get('autremontant')->getViewData();
            $autredesc=$form->get('autredesc')->getViewData();

            $signataire=$form->get('signataire')->getViewData();
            $rolesignataire=$form->get('rolesignataire')->getViewData();
            $datesignature = $form->get('datesignature')->getViewData();
            $datesignature=date("d/m/Y",strtotime($datesignature));
            $fournisseur=$modalites->getProjet()->getFournisseur()->getName();
            $reference=$modalites->getProjet()->getReference();







           try {
                $this->createpac($objet,$refpv,$datepv,$refcontrat,$facture,$refcontratsap,$boncommande,$datedebut,$datefin,$reserve1,$conditions,$pourcentage,$nomdesignation,$qttdesignation,$penalites,$retard,$retardmontant,$respect,$respectmontant,$degat,$degatmontant,$qualite,$qualitemontant,$retardfact,$retardfactmontant,$autre,$autremontant,$autredesc,$signataire,$rolesignataire,$datesignature,$fournisseur,$reference);

          }
            catch (IOException $exception){}

        }
        return $this->renderForm('modalitesof/pac.html.twig', [
            'modalite'=>$modalites,
            'projet'=>$modalites->getProjet(),
            'form' => $form,
        ]);
    }

    function createpac($objet,$refpv,$datepv,$refcontrat,$facture,$refcontratsap,$boncommande,$datedebut,$datefin,$reserve1,$conditions,$pourcentage,$nomdesignation,$qttdesignation,$penalites,$retard,$retardmontant,$respect,$respectmontant,$degat,$degatmontant,$qualite,$qualitemontant,$retardfact,$retardfactmontant,$autre,$autremontant,$autredesc,$signataire,$rolesignataire,$datesignature,$fournisseur,$reference)
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
if ($reserve1!=null){
    $tbf.='
    <div style="font-size: 12vh">
       '.$reserve1.'
</div>';
}
    else
    {$tbf.='
        <div style="font-size: 12vh">Pas de réserves</div>
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
    </table>
</div>
<div style="width: 100%;border-bottom: black 2px solid">
    <div style="font-size: 12vh">
<span style="font-weight: bold;text-decoration-line: underline">
Pénalités :
</span>
<span style="font-size: 10vh;text-decoration-line: underline">
(Mention  Obligatoire)</span> <span font-size: 10vh> Y-a-t-il  des Pénalités à appliquer ?  </span>                  
';
    if ($penalites==0) {
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

<table cellspacing="0" cellpadding="0" border="1">
    
     <tr>
       <td style="font-size:12vh; text-align: center">Nature</td>
       <td style="font-size:12vh;text-align: center">Montant (*)</td>
        
    </tr>
     <tr>
        <td style="font-size:12vh;text-align: left">
        ';
    if ($retard==true) {
        $tbf.='
                <input type="checkbox" name="agree" value="1"  checked disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                             
'; }

    else  {  $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                
';}

    $tbf.='  
  <span style="font-size:10vh">Retard dans les délais d’exécution / Livraison.</span> 
</td>
        <td style="font-size:12vh;text-align: left">'.$retardmontant.'</td>
       
    </tr>
    
    
    <tr>
        <td style="font-size:12vh;text-align: left">
        ';
    if ($respect==true) {
        $tbf.='
                <input type="checkbox" name="agree" value="1"  checked disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                             
'; }

    else  {  $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                
';}

    $tbf.='  
  <span style="font-size:10vh">Non-respect des SLA & Obligations techniques</span> 
</td>
        <td style="font-size:12vh;text-align: left">'.$respectmontant.'</td>
       
    </tr>
    
       <tr>
        <td style="font-size:12vh;text-align: left">
        ';
    if ($degat==true) {
        $tbf.='
                <input type="checkbox" name="agree" value="1"  checked disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                             
'; }

    else  {  $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                
';}

    $tbf.='  
  <span style="font-size:10vh">Dégâts</span> 
</td>
        <td style="font-size:12vh;text-align: left">'.$degatmontant.'</td>
       
    </tr> 
                 
         <tr>
        <td style="font-size:12vh;text-align: left">
        ';
    if ($qualite==true) {
        $tbf.='
                <input type="checkbox" name="agree" value="1"  checked disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                             
'; }

    else  {  $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                
';}

    $tbf.='  
  <span style="font-size:10vh">Qualité de la prestation / marchandise</span> 
</td>
        <td style="font-size:12vh;text-align: left">'.$qualitemontant.'</td>
       
    </tr>   
    
                     
         <tr>
        <td style="font-size:12vh;text-align: left">
        ';
    if ($retardfact==true) {
        $tbf.='
                <input type="checkbox" name="agree" value="1"  checked disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                             
'; }

    else  {  $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                
';}

    $tbf.='  
  <span style="font-size:10vh">Retard dans les délais de facturation</span> 
</td>
        <td style="font-size:12vh;text-align: left">'.$retardfactmontant.'</td>
       
    </tr> 
    
             <tr>
        <td style="font-size:12vh;text-align: left">
        ';
    if ($autre==true) {
        $tbf.='
                <input type="checkbox" name="agree" value="1"  checked disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                             
'; }

    else  {  $tbf.='
                <input type="checkbox" name="agree" value="0"  disabled="disabled" readonly="readonly"/> <label for="agree"></label>
                
';}

    $tbf.='  
  <span style="font-size:10vh">Autres : '.$autredesc.' </span> 
</td>
        <td style="font-size:12vh;text-align: left">'.$autredesc.'</td>
       
    </tr>  
        
    </table>
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
 <span style="font-size:9vh">(*) Le cas échant, joindre au PAC le détail de calcul de la pénalité.</span>
    
    
    
    
    
    
    
    
    
    ';












        //$pdf->WriteHTMLCell(200, 10,0,0,'', 'B', 'C', 0, 0);
        $pdf->writeHTMLCell(200, 20,5 , 0,  '<img src="/photo/entetepac.PNG">','B', 1, 0 );
        $pdf->writeHTMLCell(200,0,5,40,$tbf,0,1,0);





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
        $namefl="PAC_projet_".$reference.".pdf";
        //;
        $pdf->Output($namefl, 'D');
    }











}


