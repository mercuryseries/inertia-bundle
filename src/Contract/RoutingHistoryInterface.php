<?php

namespace MercurySeries\Bundle\InertiaBundle\Contract;

interface RoutingHistoryInterface
{
    /**
     * Get the URL for the previous request.
     */
    public function getPreviousUrl(string $fallback = null): string;
}
