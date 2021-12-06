<?php


namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

use Symfony\Component\Security\Core\Security;

use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Csrf\CsrfToken;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

use Symfony\Component\Security\Http\Util\TargetPathTrait;

use Symfony\Component\Ldap\Exception\ConnectionException;

use Symfony\Component\Ldap\Ldap;


class LdapFormAuthenticator extends AbstractFormLoginAuthenticator

{

    use TargetPathTrait;


    private $urlGenerator;

    private $csrfTokenManager;

    protected $ldap;


    public function __construct(UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, Ldap $ldap)

    {

        $this->urlGenerator = $urlGenerator;

        $this->csrfTokenManager = $csrfTokenManager;

        $this->ldap = $ldap;

    }


    public function supports(Request $request)

    {

        return 'app_login' === $request->attributes->get('_route')

            && $request->isMethod('POST');

    }


    public function getCredentials(Request $request)

    {

        $credentials = [

            'username' => $request->request->get('username'),

            'password' => $request->request->get('password'),

            'csrf_token' => $request->request->get('_csrf_token'),

        ];

        $request->getSession()->set(

            Security::LAST_USERNAME,

            $credentials['username']

        );


        return $credentials;

    }


    public function getUser($credentials, UserProviderInterface $userProvider)

    {

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);

        if (!$this->csrfTokenManager->isTokenValid($token)) {

            throw new InvalidCsrfTokenException();

        }


        $user = $userProvider->loadUserByUsername($credentials['username']);


        if (!$user) {

            throw new CustomUserMessageAuthenticationException('Username could not be found.');

        }


        return $user;

    }


    public function checkCredentials($credentials, UserInterface $user)

    {

        try {

            $this->ldap->bind($user->getEntry()->getDn(), $credentials['password']);

        } catch (ConnectionException $e) {

            return false;

        }


        return true;

    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)

    {

        $request->getSession()->getFlashBag()->add(

            'info',

            'Bienvenue. Vous êtes connecté !'

        );


        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {

            return new RedirectResponse($targetPath);

        }


        return new RedirectResponse($this->urlGenerator->generate('home_index')); // <== Adapter la route selon les besoins

    }


    protected function getLoginUrl()

    {

        return $this->urlGenerator->generate('app_login');

    }

}