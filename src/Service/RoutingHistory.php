<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RoutingHistory
{
    private readonly Request $request;

    public function __construct(
        readonly RequestStack $requestStack,

        #[Autowire('%mercuryseries_inertia_maker.routing_history.previous_url_session_key%')]
        private readonly string $previousUrlSessionKey
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Get the URL for the previous request.
     */
    public function getPreviousUrl(?string $fallback = null): string
    {
        $referrer = $this->request->headers->get('referer');

        $url = $referrer ?: $this->getPreviousUrlFromSession();

        return $url ?? $fallback ?? '/';
    }

    /**
     * Get the previous URL from the session if possible.
     */
    protected function getPreviousUrlFromSession(): ?string
    {
        return $this->request->getSession()->get($this->previousUrlSessionKey);
    }
}
