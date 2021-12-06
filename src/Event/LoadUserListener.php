<?php
namespace App\Event;

use LdapTools\Bundle\LdapToolsBundle\Event\LoadUserEvent;
use Symfony\Component\HttpFoundation\Response;

class LoadUserListener
{
    public function beforeLoadUser(LoadUserEvent $event)
    {
        /*
          // Get the username to be loaded...
          $username = $event->getUsername();
          // Get the domain for the username...
          $domain = $event->getDomain();
          // Do something with the username/domain before it hits the user provider...
          */
    }

    public function afterLoadUser(LoadUserEvent $event)
    {

        /*$userToken = $event->getAuthenticationToken();
        if ($this->ldapManager->authenticate($event->getUsername(), $this->requestStack->getCurrentRequest()->get('password'))) {//good credentials
            $token = new UsernamePasswordToken($userToken, 'yes', "public", $userToken->getRoles());
            $this->container->get('security.token_storage')->setToken($token);//set a token

            $event = new InteractiveLoginEvent($this->requestStack->getCurrentRequest(), $token); //dispatch the auth event
            $event->stopPropagation();
            $this->container->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN,$event);

        }*/
        /*
          // Get the username that was loaded...
          $username = $event->getUsername();
          // Get the domain for the username...
          $domain = $event->getDomain();
          // Get the actual user instance...
          $user = $event->getUser();
          // Get the LDAP object the user was loaded from...
          $ldapObject = $event->getLdapObject();
          // Do something with the user/username/domain/LDAP attributes before it is authenticated...
          foreach($ldapObject->toArray() as $attribute => $value) {
              # ...
          }
          */
    }
}