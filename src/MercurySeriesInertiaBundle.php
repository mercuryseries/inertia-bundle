<?php

namespace MercurySeries\Bundle\InertiaBundle;

use MercurySeries\Bundle\InertiaBundle\DependencyInjection\MercurySeriesInertiaExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MercurySeriesInertiaBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new MercurySeriesInertiaExtension();
        }

        return $this->extension;
    }
}
