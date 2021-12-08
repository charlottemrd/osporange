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

           // $r = $this->ldapasker->getfullname('UserappWeb_Manager');

        $ldaprdn = $_ENV['USERNAME_ADMIN'];
        $ldappass = $_ENV['PSWD_ADMIN'];
        $ldapconn = ldap_connect($_ENV['IP_SERVER']);

        $upn = $this->security->getUser()->getUsername() . '@' . $_ENV['DOMAINE_NAME'];
       ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);


        if ($ldapconn) {
            $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

            if ($ldapbind) {
                $attributes = ['displayname'];
                $filter = "(&(objectClass=user)(objectCategory=person)(userPrincipalName=" . ldap_escape($upn, null, LDAP_ESCAPE_FILTER) . "))";
                $baseDn = $_ENV['BASE_OF_DN'];
                $results = ldap_search($ldapconn, $baseDn, $filter, $attributes);
                $info = ldap_get_entries($ldapconn, $results);
                if(isset($info[0]['displayname'][0])){
                $usernametoget= $info[0]['displayname'][0];
                $this->security->getToken()->setAttribute('full name',$usernametoget );}
                else{
                    $attributesb = ['samaccountname'];
                    $resultsb = ldap_search($ldapconn, $baseDn, $filter, $attributesb);
                    $infob = ldap_get_entries($ldapconn, $resultsb);
                    $usernametogetb= $infob[0]['samaccountname'][0];
                    $this->security->getToken()->setAttribute('fullname',$usernametogetb );}

            }
        }









        /**
         * Update lastLogin and loginCount for user in database
         */

//  $em->

    }

}