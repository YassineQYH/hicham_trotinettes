<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;



    private $entityManager;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response {

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // ⚡ Ici on récupère la session et on “force” le typage pour l'IDE
        /** @var SessionInterface $session */
        $session = $request->getSession();
        if (!$session instanceof SessionInterface) {
            throw new \LogicException('La session est requise pour les flashs');
        }

        // ✅ Ajout du flash pour l'utilisateur connecté
        $session->getFlashBag()->add(
            'info-alert',
            'Heureux de vous revoir 👋'
        );

        return new RedirectResponse(
            $this->urlGenerator->generate('app_home')
        );
    }


    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {

        /** @var SessionInterface $session */
        $session = $request->getSession();
        if (!$session instanceof SessionInterface) {
            throw new \LogicException('La session est requise pour les flashs');
        }

        $session->getFlashBag()->add(
            'error',
            'Identifiants incorrects. Veuillez réessayer.'
        );

        return new RedirectResponse(
            $this->urlGenerator->generate('app_home')
        );
    }


    protected function getLoginUrl(Request $request): string
    {
        if(isset($_POST["type"]) && $_POST["type"] =="login")
        //var_dump($_POST);
        //dd("if register");
        return $this->urlGenerator->generate($request->get('_route'), $request->get('_route_params')); // LOGIN : OK
        else
        //var_dump($_POST);
        //dd("if register");
        return new RedirectResponse('targetPath'); // REGISTER : OK
    }

}
