<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Service;

interface RoutingHistoryInterface
{
    /**
     * Get the URL for the previous request.
     */
    public function getPreviousUrl(?string $fallback = null): string;
}
