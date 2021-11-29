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


#[Route('/listemodalites')]
class ModalitesofController extends AbstractController
{
    #[Route('/', name: 'modalitesof_index', methods: ['GET','POST'])]
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



    #[Route('/{id}', name: 'modalitesof_show', methods: ['GET','POST'])]
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
                    'redirect' => $this->generateUrl('projet_index')  //rediriger apres vers editer pva

                ),
                    200);
            }
        }








        return $this->render('modalitesof/show.html.twig', [
            'projet'=>$projet,
            'modalites'=>$modalitesRepository->findBy(array('projet'=>$projet),array('pourcentage'=>'ASC')),
        ]);
    }




    #[Route('/{id}/pac', name: 'modalitesof_pac', methods: ['GET', 'POST'])]
    public function pac(Request $request, Modalites $modalites): Response
    {
        $form = $this->createForm(PacType::class, $modalites);
        $form->get('signataire')->setData($this->getUser());
        $form->get('datesignature')->setData(new \DateTime());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           /* $referencefl = $form->get('reference')->getData();
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
            catch (IOException $exception){}*/

        }
        return $this->renderForm('modalitesof/pac.html.twig', [
            'modalite'=>$modalites,
            'projet'=>$modalites->getProjet(),
            'form' => $form,
        ]);
    }

    function createpac($referencefl,$prioritefl,$dateemisfl,$emetteurfl,$sujetfl,$descriptionfl,$piecejointesfl){

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


