<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use MercurySeries\Bundle\InertiaMakerBundle\Contract\RoutingHistoryInterface;

final class RoutingHistory implements RoutingHistoryInterface
{
    private readonly Request $request;

    public function __construct(
        readonly RequestStack $requestStack,

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
