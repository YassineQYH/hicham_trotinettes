<?php

namespace App\EventSubscriber;

use App\Service\SiteConfigService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private SiteConfigService $siteConfigService;
    private Security $security;
    private RouterInterface $router;

    public function __construct(
        SiteConfigService $siteConfigService,
        Security $security,
        RouterInterface $router
    ) {
        $this->siteConfigService = $siteConfigService;
        $this->security = $security;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', -10], // priorité négative pour que l'utilisateur soit déjà connu
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
{
    if (!$event->isMainRequest()) {
        return;
    }

    if (!$this->siteConfigService->isMaintenanceEnabled()) {
        return;
    }

    $request = $event->getRequest();
    $route   = $request->attributes->get('_route');
    $path    = $request->getPathInfo();

    $allowedRoutes = [
        'app_maintenance',
        'app_login',
        'app_logout',
    ];

    // 1️⃣ Admin → accès total
    if ($this->security->isGranted('ROLE_ADMIN')) {
        return;
    }

    // 2️⃣ Back-office non-admin → redirection maintenance
    if (str_starts_with($path, '/admin')) {
        $event->setResponse(new RedirectResponse($this->router->generate('app_maintenance')));
        return;
    }

    // 3️⃣ Front non autorisé → redirection maintenance
    if (!in_array($route, $allowedRoutes)) {
        $event->setResponse(new RedirectResponse($this->router->generate('app_maintenance')));
        return;
    }
}

}
