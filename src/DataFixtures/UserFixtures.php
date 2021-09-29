<?php

namespace App\DataFixtures;

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
        $phase->setOrdere('10');
        $manager->persist($phase);

        $phase2 = new Phase();
        $phase2->setName('stand-by');
        $phase2->setRang('2');
        $phase2->setOrdere('11');
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
        $phase6->setName('en construction');
        $phase6->setRang('6');
        $phase6->setOrdere('6');
        $manager->persist($phase6);

        $phase7 = new Phase();
        $phase7->setName('en test');
        $phase7->setRang('7');
        $phase7->setOrdere('7');
        $manager->persist($phase7);

        $phase8 = new Phase();
        $phase8->setName('en recette');
        $phase8->setRang('8');
        $phase8->setOrdere('8');
        $manager->persist($phase8);

        $phase9 = new Phase();
        $phase9->setName('en production');
        $phase9->setRang('9');
        $phase9->setOrdere('9');
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












        $manager->flush();
    }
}
