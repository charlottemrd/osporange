<?php
namespace App\Event;

use LdapTools\Bundle\LdapToolsBundle\Event\LdapLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;



class LdapLoginListener
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onLdapLoginSuccess(LdapLoginEvent $event)
    {
        #return new RedirectResponse($this->router->generate('projet_index'));
    }


    public function onLdapLoginFailure(LdapLoginEvent $event)
    {


    }
}


namespace AppBundle\Security\Authentication\Handler;

