<?php

namespace MercurySeries\Bundle\InertiaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class MercurySeriesInertiaExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        // prepend the rompetomp_inertia settings with the ssr config
        $container->prependExtensionConfig('rompetomp_inertia', [
            'ssr' => [
                'enabled' => $config['ssr']['enabled'],
                'url' => $config['ssr']['url'],
            ],
        ]);

        // prepend the dneustadt_csrf_cookie settings with the csrf_cookie config
        $container->prependExtensionConfig('dneustadt_csrf_cookie', $config['csrf_cookie']);

        // set SSR config as container parameters
        foreach ($config['ssr'] as $key => $value) {
            $container->setParameter('mercuryseries_inertia.ssr.'.$key, $value);
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');
    }

    public function getAlias(): string
    {
        return 'mercuryseries_inertia';
    }
}
