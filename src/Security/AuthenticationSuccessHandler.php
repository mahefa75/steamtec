<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $urlGenerator;
    private $params;

    public function __construct(UrlGeneratorInterface $urlGenerator, ParameterBagInterface $params)
    {
        $this->urlGenerator = $urlGenerator;
        $this->params = $params;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();
        
        // Si l'utilisateur a le rôle ADMIN, redirection vers /admin
        if (in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return new RedirectResponse($this->params->get('ADMIN_REDIRECT_PATH'));
        }
        
        // Sinon, redirection vers la page d'accueil
        return new RedirectResponse('/');
    }
} 