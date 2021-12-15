<?php

namespace App\Event;

use Doctrine\ORM\EntityManagerInterface;
use LdapTools\Bundle\LdapToolsBundle\Event\LoadUserEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
class InteractiveLoginListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }



    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {







        /**
         * Update lastLogin and loginCount for user in database
         */

//  $em->

    }

}