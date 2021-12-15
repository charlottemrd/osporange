<?php

namespace App\Event;

use App\Entity\AppUser;
use Doctrine\ORM\EntityManagerInterface;
use LdapTools\Bundle\LdapToolsBundle\Event\LdapLoginEvent;
use LdapTools\Object\LdapObjectType;
use LdapTools\Configuration;
use LdapTools\LdapManager;


class LdapLoginListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param LdapLoginEvent $event
     */
    public function onLoginSuccess(LdapLoginEvent $event )
    {
        /** @var User $user */
        $user = $event->getUser();

        // If the ID doesn't exist, then it hasn't been saved to the database. So save it..
        if (!$user->getId()) {


            $ldaprdn = $_ENV['USERNAME_ADMIN'];
            $ldappass = $_ENV['PSWD_ADMIN'];
            $ldapconn = ldap_connect($_ENV['IP_SERVER']);
            $usernametoget='';
            $upn = $event->getUser()->getUsername() . '@' . $_ENV['DOMAINE_NAME'];
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

                    if (isset($info[0]['displayname'][0])) {
                        $usernametoget = $info[0]['displayname'][0];
                    } else {
                        $attributesb = ['samaccountname'];
                        $resultsb = ldap_search($ldapconn, $baseDn, $filter, $attributesb);
                        $infob = ldap_get_entries($ldapconn, $resultsb);
                        $usernametoget = $infob[0]['samaccountname'][0];
                    }
                }

            }

            $user->setFullusername($usernametoget);
            $this->em->persist($user);
            $this->em->flush();

        }

    }
}
