<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class MercurySeriesInertiaMakerExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('mercuryseries_inertia_maker.ssr.enabled', $config['ssr']['enabled']);
        $container->setParameter('mercuryseries_inertia_maker.ssr.bundle', $config['ssr']['bundle']);
        $container->setParameter('mercuryseries_inertia_maker.ssr.url', $config['ssr']['url']);

        // $rootNamespace = trim($config['root_namespace'], '\\');

        // $inertiaMakeCommandDefinition = $container->getDefinition('mercuryseries_inertia_maker.generator');
        // $inertiaMakeCommandDefinition->replaceArgument(1, $rootNamespace);

        // https://github.com/KnpLabs/KnpPaginatorBundle/blob/master/src/DependencyInjection/KnpPaginatorExtension.php
        // $definition = new Definition(ExceptionListener::class);
        // $definition->addTag('kernel.event_listener', ['event' => 'kernel.exception']);
        // $container->setDefinition(ExceptionListener::class, $definition);
    }

    public function prepend(ContainerBuilder $container)
    {
        // ...
    }

    public function getAlias(): string
    {
        return 'mercuryseries_inertia_maker';
    }
}
