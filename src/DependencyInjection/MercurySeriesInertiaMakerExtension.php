<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class MercurySeriesInertiaMakerExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());

        // iterate in reverse to preserve the original order after prepending the config
        foreach (array_reverse($configs) as $config) {
            // check if ssr is set in the "mercuryseries_inertia_maker" configuration
            if (isset($config['ssr'])) {
                $ssrConfig = $config['ssr'];

                // prepend the rompetomp_inertia settings with the ssr config
                $container->prependExtensionConfig('rompetomp_inertia', [
                    'ssr' => [
                        'enabled' => $ssrConfig['enabled'],
                        'url' => $ssrConfig['url']
                    ]
                ]);

                // set required container parameters
                $container->setParameter('mercuryseries_inertia_maker.ssr.enabled', $ssrConfig['enabled']);
                $container->setParameter('mercuryseries_inertia_maker.ssr.url', $ssrConfig['url']);
                $container->setParameter('mercuryseries_inertia_maker.ssr.bundle', $ssrConfig['bundle']);
            }

            if (isset($config['csrf_cookie'])) {
                // prepend the dneustadt_csrf_cookie settings with the csrf_cookie config
                $container->prependExtensionConfig('dneustadt_csrf_cookie', $config['csrf_cookie']);
            }
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');
    }

    public function getAlias(): string
    {
        return 'mercuryseries_inertia_maker';
    }
}
