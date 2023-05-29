<?php

namespace MercurySeries\Bundle\InertiaMakerBundle;

use MercurySeries\Bundle\InertiaMakerBundle\DependencyInjection\MercurySeriesInertiaMakerExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MercurySeriesInertiaMakerBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new MercurySeriesInertiaMakerExtension();
        }

        return $this->extension;
    }
}
