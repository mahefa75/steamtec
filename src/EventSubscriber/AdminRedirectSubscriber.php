<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AdminRedirectSubscriber implements EventSubscriberInterface
{
    private $security;
    private $params;

    public function __construct(Security $security, ParameterBagInterface $params)
    {
        $this->security = $security;
        $this->params = $params;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Ne rien faire si ce n'est pas la requête principale
        if (!$event->isMainRequest()) {
            return;
        }

        // Vérifier si l'utilisateur est connecté et a le rôle ADMIN
        if ($this->security->isGranted('ROLE_ADMIN')) {
            // Si l'utilisateur est sur la page d'accueil
            if ($request->getPathInfo() === '/') {
                // Rediriger vers /admin
                $event->setResponse(new RedirectResponse($this->params->get('ADMIN_REDIRECT_PATH')));
            }
        }
    }
} 