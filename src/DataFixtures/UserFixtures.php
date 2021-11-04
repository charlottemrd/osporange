<?php

namespace App\DataFixtures;

use App\Entity\Bilanmensuel;
use App\Entity\Cout;
use App\Entity\Fournisseur;
use App\Entity\Idmonthbm;
use App\Entity\Infobilan;
use App\Entity\Profil;
use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Phase;
use App\Entity\Priorite;
use App\Entity\Risque;
use App\Entity\TypeBU;
use App\Entity\Paiement;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('john.kind@gmail.coms');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Test1234'));

        $user->setName('John Kind');
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('marcel.abc@gmail.coms');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setPassword($this->passwordEncoder->encodePassword($user2,'Test1234'));
        $user2->setName('Marcel ABC');
        $manager->persist($user2);


        $paiement = new Paiement();
        $paiement->setType('bilan mensuel');
        $manager->persist($paiement);

        $paiement2 = new Paiement();
        $paiement2->setType('paiement par modalités');
        $manager->persist($paiement2);

        $typebu = new TypeBU();
        $typebu->setType('B2B');
        $manager->persist($typebu);

        $typebu2 = new TypeBU();
        $typebu2->setType('B2C');
        $manager->persist($typebu2);

        $typebu3 = new TypeBU();
        $typebu3->setType('fixe');
        $manager->persist($typebu3);

        $typebu4 = new TypeBU();
        $typebu4->setType('interne');
        $manager->persist($typebu4);


        $priorite = new Priorite();
        $priorite->setNiveau('basse');
        $manager->persist($priorite);

        $priorite2 = new Priorite();
        $priorite2->setNiveau('moyenne');
        $manager->persist($priorite2);

        $priorite3 = new Priorite();
        $priorite3->setNiveau('haute');
        $manager->persist($priorite3);



        $phase = new Phase();
        $phase->setName('abandonné');
        $phase->setRang('1');
        $phase->setOrdere('11');
        $manager->persist($phase);

        $phase2 = new Phase();
        $phase2->setName('stand-by');
        $phase2->setRang('2');
        $phase2->setOrdere('12');
        $manager->persist($phase2);

        $phase3 = new Phase();
        $phase3->setName('non demarré');
        $phase3->setRang('3');
        $phase3->setOrdere('3');
        $manager->persist($phase3);

        $phase4 = new Phase();
        $phase4->setName('cadrage');
        $phase4->setRang('4');
        $phase4->setOrdere('4');
        $manager->persist($phase4);

        $phase5 = new Phase();
        $phase5->setName('en étude');
        $phase5->setRang('5');
        $phase5->setOrdere('5');
        $manager->persist($phase5);

        $phase6 = new Phase();
        $phase6->setName('en construction - étape de conception');
        $phase6->setRang('6');
        $phase6->setOrdere('6');
        $manager->persist($phase6);

        $phase0 = new Phase();
        $phase0->setName('en construction - étape de codage');
        $phase0->setRang('7');
        $phase0->setOrdere('7');
        $manager->persist($phase0);

        $phase7 = new Phase();
        $phase7->setName('en test');
        $phase7->setRang('8');
        $phase7->setOrdere('8');
        $manager->persist($phase7);

        $phase8 = new Phase();
        $phase8->setName('en recette');
        $phase8->setRang('9');
        $phase8->setOrdere('9');
        $manager->persist($phase8);

        $phase9 = new Phase();
        $phase9->setName('en production');
        $phase9->setRang('10');
        $phase9->setOrdere('10');
        $manager->persist($phase9);



        $risque = new Risque();
        $risque->setName('maitrisé');
        $risque->setRang('1');
        $manager->persist($risque);

        $risque2 = new Risque();
        $risque2->setName('à surveiller');
        $risque2->setRang('2');
        $manager->persist($risque2);

        $risque3 = new Risque();
        $risque3->setName('pose problème');
        $risque3->setRang('3');
        $manager->persist($risque3);



        //dataset for bilanmensuel :essai
        $fournisseur1=new Fournisseur();
        $fournisseur1->setName('fournisseur A');
        $fournisseur1->setDevise('a');

        $profil1=new Profil();
        $profil1->setName('a');
        $profil1->setTarif(100);
        $profil1->setFournisseur($fournisseur1);
        $profil2=new Profil();
        $profil2->setName('b');
        $profil2->setTarif(100);
        $profil2->setFournisseur($fournisseur1);
        $fournisseur1->getProfils()->add($profil1);
        $fournisseur1->getProfils()->add($profil2);

        $manager->persist($fournisseur1);


        $projet1=new Projet();
        $projet1->setTypebu($typebu);
        $projet1->setFournisseur($fournisseur1);
        $projet1->setPhase($phase6);
        $projet1->setDate0(new \DateTime());
        $projet1->setDate1(new \DateTime());
        $projet1->setDate2(new \DateTime());
        $projet1->setDate3(new \DateTime());
        $projet1->setDatel1(new \DateTime());
        $projet1->setHighestphase(6);
        $projet1->setReference("xsza");
        $projet1->setDomaine("dxzs");
        $projet1->setSdomaine("xczs");
        $projet1->setDescription("dxqez");
        $projet1->setTaux(0);
        $projet1->setIsplanningrespecte(0);
        $projet1->setPaiement($paiement);
        $projet1->setRisque($risque2);
        $projet1->setPriorite($priorite2);
        $projet1->setUser($user);
        $cout1=new Cout();
        $cout1->setProfil($profil1);
        $cout1->setProjet($projet1);
        $cout1->setNombreprofil(100);

        $cout2=new Cout();
        $cout2->setProfil($profil2);
        $cout2->setProjet($projet1);
        $cout2->setNombreprofil(200);

        $projet1->getCouts()->add($cout1);
        $projet1->getCouts()->add($cout2);

        $manager->persist($projet1);

        $projet2=new Projet();
        $projet2->setTypebu($typebu);
        $projet2->setFournisseur($fournisseur1);
        $projet2->setPhase($phase6);
        $projet2->setDate0(new \DateTime());
        $projet2->setDate1(new \DateTime());
        $projet2->setDate2(new \DateTime());
        $projet2->setDate3(new \DateTime());
        $projet2->setDatel1(new \DateTime());
        $projet2->setHighestphase(6);
        $projet2->setReference("huo");
        $projet2->setDomaine("dxzs");
        $projet2->setSdomaine("xczs");
        $projet2->setDescription("dxqez");
        $projet2->setTaux(0);
        $projet2->setIsplanningrespecte(0);
        $projet2->setPaiement($paiement);
        $projet2->setRisque($risque2);
        $projet2->setPriorite($priorite2);
        $projet2->setUser($user);
        $cout3=new Cout();
        $cout3->setProfil($profil1);
        $cout3->setProjet($projet2);
        $cout3->setNombreprofil(0);

        $cout4=new Cout();
        $cout4->setProfil($profil2);
        $cout4->setProjet($projet2);
        $cout4->setNombreprofil(0);

        $projet2->getCouts()->add($cout3);
        $projet2->getCouts()->add($cout4);

        $manager->persist($projet1);



        $infmon=new Idmonthbm();
        $infmon->setMonthyear(new \DateTime());
        $infmon->setIsaccept(0);
        $infmon->setFournisseur($fournisseur1);
        $manager->persist($infmon);

        $bilan1=new Bilanmensuel();
        $bilan1->setProjet($projet1);
        $bilan1->setHavebeenmodified(0);
        //$bilan1->setMonthyear(new \DateTime());
        $bilan1->setIdmonthbm($infmon);

        $info1=new Infobilan();
        $info1->setNombreprofit(0);
        $info1->setProfil($profil1);
        $info1->setBilanmensuel($bilan1);
        $manager->persist($info1);

        $info2=new Infobilan();
        $info2->setNombreprofit(0);
        $info2->setProfil($profil2);
        $info2->setBilanmensuel($bilan1);
        $manager->persist($info2);

       /* $info2=new Infobilan();
        $info2->setNombreprofit(0);
        $info2->setProfil($profil2);
        $info2->setBilanmensuel($bilan1);
        $bilan1->getInfobilans()->add($info2);*/

        $manager->persist($bilan1);

        $bilan2=new Bilanmensuel();
        $bilan2->setProjet($projet2);
        $bilan2->setHavebeenmodified(0);
        $bilan2->setIdmonthbm($infmon);
        //$bilan2->setMonthyear(new \DateTime());

        $info3=new Infobilan();
        $info3->setNombreprofit(0);
        $info3->setProfil($profil1);
        $info3->setBilanmensuel($bilan2);
        $manager->persist($info3);

        $info4=new Infobilan();
        $info4->setNombreprofit(0);
        $info4->setProfil($profil2);
        $info4->setBilanmensuel($bilan2);
        $manager->persist($info4);



        $manager->persist($bilan2);








        $manager->flush();
    }
}
