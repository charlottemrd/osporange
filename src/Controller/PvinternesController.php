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

#[Route('/pvinternes')]
class PvinternesController extends AbstractController
{
    #[Route('/', name: 'pvinternes_index', methods: ['GET'])]
    public function index(DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        $data=new SearchDatePv();
        $form=$this->createForm(SearchDatePvinterne::class,$data);
        $form->handleRequest($request);
        $datepvs=$datepvinterneRepository->searchbilanmensuelfournisseur($data);
        return $this->render('pvinternes/index.html.twig', [
            'datepvs'=>$datepvs,

            'form'=>$form->createView(),
        ]);


    }

    #[Route('/date/{datepvinterne}/{month}/{year}', name: 'pvinternesdate', methods: ['GET'])]
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

    #[Route('/modification/{pvinternes}/{id}', name: 'modifypv', methods: ['GET','POST'])]
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
            $taux = $request->request->get('taux');
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
                    if ($taux <= $maxpv) {
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
                    if ($taux <= $maxpv) {
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
                                    $pvinterne->setIsmodified(false);
                                    $pvinterne->setIsvalidate(false);
                                    $pvinterne->setPourcentage(0);
                                    $this->getDoctrine()->getManager()->persist($pvinterne);
                                    $this->getDoctrine()->getManager()->flush();

                                } else { // on cree tout

                                    $datepvinterne = new Datepvinterne();
                                    $datepvinterne->setDatemy($sched);
                                    $pvinterne = new Pvinternes();
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
                                'redirect' => $this->generateUrl('projet_new')
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

    #[Route('/pvinterne/{pvinternes}/{id}', name: 'showpv', methods: ['GET','POST'])]
    public function show(NotifierInterface $notifier, PvinternesRepository $pvinternesRepository,Datepvinterne $datepvinterne, Pvinternes $pvinternes,DatepvinterneRepository $datepvinterneRepository,Request $request)
    {
        return $this->render('pvinternes/show.html.twig', [
            'datepv'=>$datepvinterne,
            'pv'=>$pvinternes,

        ]);


    }



    #[Route('/{id}/pvr', name: 'pvrinternes_pvr', methods: ['GET', 'POST'])]
    public function pvr(Request $request, Pvinternes $pvinternes): Response
    {
        $form = $this->createForm(PvrType::class, $pvinternes);
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







            try {
                //$this->createpvr($objet,$refpv,$datepv,$datepv,$refcontrat,$facture,$refcontratsap,$boncommande,$datedebut,$datefin,$reservemineure,$reservemajeure,$conditions,$pourcentage,$nomdesignation, $qttdesignation, $nomdesignation2, $qttdesignation2,$bonapayer, $signataire, $rolesignataire, $datesignature, $datesignature, $fournisseur);
            }
            catch (IOException $exception){}

        }
        return $this->renderForm('pvinternes/pvr.html.twig', [
            'pvinternes'=>$pvinternes,
            'projet'=>$pvinternes->getProjet(),
            'form' => $form,
        ]);
    }








}
