<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Cookie;

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
                ->arrayNode('csrf_cookie')
                    ->children()
                        ->integerNode('expire')
                            ->info('Number of seconds after which the CSRF token cookie expires')
                            ->defaultValue(0)
                        ->end()
                        ->scalarNode('path')
                            ->info('Cookie path')
                            ->defaultValue('/')
                        ->end()
                        ->scalarNode('domain')
                            ->info('Cookie domain')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('secure')
                            ->info('Cookie secure')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('sameSite')
                            ->info('Cookie same site policy')
                            ->defaultValue(Cookie::SAMESITE_LAX)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
