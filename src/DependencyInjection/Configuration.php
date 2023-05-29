<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('mercuryseries_inertia_maker');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('ssr')
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Whether or not to enable server-side-rendering')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('url')
                            ->info('URL of the SSR server')
                            ->defaultValue('http://127.0.0.1:13714')
                        ->end()
                        ->scalarNode('bundle')
                            ->info('Custom SSR bundle path. Leave it empty to let Inertia try to automatically detect it for you')
                            ->defaultNull()
                            ->example('%kernel.project_dir%/public/build-ssr/ssr.mjs')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
