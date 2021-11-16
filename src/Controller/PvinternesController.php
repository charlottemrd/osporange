<?php

namespace App\Controller;

use App\Entity\Datepvinterne;
use App\Entity\Pvinternes;
use App\Entity\SearchDatePv;
use App\Entity\Searchpv;
use App\Form\Pvinterneform;
use App\Form\PvinternesType;
use App\Entity\SearchProjetPv;
use App\Form\SearchDatePvinterne;
use App\Form\SearchpvType;
use App\Repository\DatepvinterneRepository;
use App\Repository\PvinternesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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


                            $this->getDoctrine()->getManager()->flush();
                            return new JsonResponse(array( //cas succes
                                'status' => 'OK',
                                'message' => 'le bilan mensuel a bien été modifié',
                                'success' => true,
                                'redirect' => $this->generateUrl('modifypv', ['pvinternes' => $datepvinterne->getId(), 'id' => $pvinternes->getId()])
                            ),
                                200);

                        }
                    }
                }


            }// fin cas où on modifie le bilan mensuel
        }








        return $this->render('pvinternes/modify.html.twig', [
            'datepv'=>$datepvinterne,
            'pv'=>$pvinternes,
            'maxp'=>$maxpv,
            'form'=>$form->createView(),

        ]);


    }



}
