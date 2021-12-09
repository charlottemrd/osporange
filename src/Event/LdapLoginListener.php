<?php
namespace App\Event;

use LdapTools\Bundle\LdapToolsBundle\Event\LdapLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class LdapLoginListener
{
    private $router;
    private $security;
    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onLdapLoginSuccess(LdapLoginEvent $event)
    {



    }


    public function onLdapLoginFailure(LdapLoginEvent $event)
    {


    }
}


namespace AppBundle\Security\Authentication\Handler;

