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


        if ($request->isXmlHttpRequest()) {

            $type = $request->request->get('type');
            if ($type == 1) {
                $indexofm = $request->request->get('index');
                $guidof = $request->request->get('guid');

                $guid = $ldapManager->buildLdapQuery()
                    ->select('cn')
                    ->fromUsers()
                    ->where(['guid' => $guidof])
                    ->getLdapQuery()
                    ->getSingleScalarResult();

                return new JsonResponse(array( //cas succes
                    'indexofr' => $indexofm,
                    'message' => $guid,
                    'success' => true,
                ),
                    200);
            }}

        if ($form->isSubmitted() && $form->isValid()) {

            if($fournisseur->getFournisseurfullname()==null){
                $ldaprdn = $_ENV['USERNAME_ADMIN'];
                $ldappass = $_ENV['PSWD_ADMIN'];
                $ldapconn = ldap_connect($_ENV['IP_SERVER']);
                $usernametoget='';
                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);


                if ($ldapconn) {
                    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

                    if ($ldapbind) {
                        $attributes = ['displayname'];
                        $filter = "(&(objectClass=user)(objectCategory=person)(distinguishedname=" . ldap_escape($fournisseur->getFournisseurldap(), null, LDAP_ESCAPE_FILTER) . "))";
                        $baseDn = $_ENV['BASE_OF_DN'];
                        $results = ldap_search($ldapconn, $baseDn, $filter, $attributes);
                        $info = ldap_get_entries($ldapconn, $results);

                        if(isset($info[0]['displayname'][0])){
                            $usernametogetd= $info[0]['displayname'][0];
                        }
                        else{
                            $attributesb = ['samaccountname'];
                            $resultsb = ldap_search($ldapconn, $baseDn, $filter, $attributesb);
                            $infob = ldap_get_entries($ldapconn, $resultsb);

                            $usernametogetd= $infob[0]['samaccountname'][0];
                        }

                    }

                    $fournisseur->setFournisseurfullname($usernametogetd);
                }




            }

            if($fournisseur->getFournisseurid()==null){
                $ldaprdnc = $_ENV['USERNAME_ADMIN'];
                $ldappassc = $_ENV['PSWD_ADMIN'];
                $ldapconnc = ldap_connect($_ENV['IP_SERVER']);
                $usernametogetc='';
                ldap_set_option($ldapconnc, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldapconnc, LDAP_OPT_REFERRALS, 0);


                if ($ldapconnc) {
                    $ldapbindc = ldap_bind($ldapconnc, $ldaprdnc, $ldappassc);

                    if ($ldapbindc) {
                        $attributesc = ['objectguid'];
                        $filterc = "(&(objectClass=user)(objectCategory=person)(distinguishedname=" . ldap_escape($fournisseur->getFournisseurldap(), null, LDAP_ESCAPE_FILTER) . "))";
                        $baseDnc = $_ENV['BASE_OF_DN'];
                        $resultsc = ldap_search($ldapconnc, $baseDnc, $filterc, $attributesc);
                        $infoc = ldap_get_entries($ldapconnc, $resultsc);

                        if(isset($infoc[0]['objectguid'][0])){
                            $usernametogetc= $infoc[0]['objectguid'][0];
                        }
                        else{
                            $attributesd = ['name'];
                            $resultsd = ldap_search($ldapconnc, $baseDnc, $filterc, $attributesd);
                            $infod = ldap_get_entries($ldapconnc, $resultsd);
                            $usernametogetd= $infod[0]['name'][0];
                        }

                    }

                    $fournisseur->setFournisseurid($usernametogetd);
                }




            }


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
