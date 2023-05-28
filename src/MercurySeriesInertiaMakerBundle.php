<?php

namespace MercurySeries\Bundle\InertiaMaker;

use MercurySeries\Bundle\InertiaMaker\DependencyInjection\MercurySeriesInertiaMakerExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MercurySeriesInertiaMakerBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new MercurySeriesInertiaMakerExtension();
        }

        return $this->extension;
    }
}
