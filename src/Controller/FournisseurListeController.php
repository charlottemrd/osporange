<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Profil;
use App\Form\FournisseurType;
use App\Form\EditfournisseurType;
use App\Repository\FournisseurRepository;
use App\Repository\ProfilRepository;
use LdapTools\LdapManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
/**
 * @Route("/fournisseur/liste")
 */
class FournisseurListeController extends AbstractController
{

    /**
     * @Route("/", name="fournisseur_liste_index",methods={"GET"})
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('fournisseur_liste/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fournisseur_liste_new",methods={"GET","POST"})
     */
    public function new(LdapManager $ldapManager , Request $request, NotifierInterface $notifier): Response
    {
        $fournisseur = new Fournisseur();




        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {





            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fournisseur);
            $entityManager->flush();
            $notifier->send(new Notification('Le fournisseur a bien été ajouté', ['browser']));
            return $this->redirectToRoute('fournisseur_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fournisseur_liste/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fournisseur_liste_show",methods={"GET"})
     */
    public function show(Fournisseur $fournisseur): Response
    {
        return $this->render('fournisseur_liste/show.html.twig', [
            'fournisseur' => $fournisseur,
            'profils' => $fournisseur->getProfils(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fournisseur_liste_edit",methods={"GET","POST"})
     */
    public function edit(LdapManager $ldapManager, Request $request, Fournisseur $fournisseur): Response
    {
        $form = $this->createForm(EditFournisseurType::class, $fournisseur);
        $form->handleRequest($request);





        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fournisseur_liste_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('fournisseur_liste/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="fournisseur_liste_delete",methods={"POST"})
     */
    public function delete(Request $request, Fournisseur $fournisseur,NotifierInterface $notifier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }
        $notifier->send(new Notification('Le fournisseur a bien été supprimé', ['browser']));
        return $this->redirectToRoute('fournisseur_liste_index', [], Response::HTTP_SEE_OTHER);
    }
}
