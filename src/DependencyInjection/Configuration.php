<?php

namespace MercurySeries\Bundle\InertiaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Cookie;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('mercuryseries_inertia');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('ssr')->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Whether or not to enable server-side-rendering. Defaults to false.')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('url')
                            ->cannotBeEmpty()
                            ->info('The URL of the server-side rendering (SSR) server. Defaults to "http://127.0.0.1:13714".')
                            ->defaultValue('http://127.0.0.1:13714')
                        ->end()
                        ->scalarNode('bundle')
                            ->info('The path to a custom SSR bundle. If left empty, Inertia will attempt to automatically detect the bundle for you.')
                            ->defaultNull()
                            ->example('%kernel.project_dir%/public/build-ssr/ssr.mjs')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('csrf_cookie')->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('expire')
                            ->info('Number of seconds after which the CSRF token cookie expires. Defaults to 0 for a session cookie.')
                            ->defaultValue(0)
                        ->end()
                        ->scalarNode('path')
                            ->cannotBeEmpty()
                            ->info('The path on the server in which the cookie will be available. Defaults to "/" (the entire website).')
                            ->defaultValue('/')
                        ->end()
                        ->scalarNode('domain')
                            ->info('The domain that the cookie is available to. If not set, the cookie is available to the current domain.')
                            ->defaultNull()
                        ->end()
                        ->booleanNode('secure')
                            ->info('Indicates whether the cookie should only be transmitted over HTTPS. Defaults to true.')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('sameSite')
                            ->cannotBeEmpty()
                            ->info('The SameSite attribute of the cookie, which restricts how the cookie is sent in cross-site requests. Defaults to "lax".')
                            ->defaultValue(Cookie::SAMESITE_LAX)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
