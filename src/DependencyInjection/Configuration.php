<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_personio');
        $treeBuilder
            ->getRootNode()
                ->children()
                ->scalarNode('company_id')
                    ->info('The Company ID from Personio.')
                    ->defaultNull()
                    ->beforeNormalization()->always(static fn (mixed $value): string => (string) $value)->end()
                ->end()
                ->scalarNode('xml_feed')
                    ->info('The URL to the job XML feed of the company, i.e. https://{YOUR_COMPANY}.jobs.personio.de/xml')
                    ->defaultNull()
                ->end()
                ->scalarNode('recruiting_api_client_id')
                    ->info('The Personio recruiting API client id.')
                    ->defaultNull()
                ->end()
                ->scalarNode('recruiting_api_client_secret')
                    ->info('The Personio recruiting API client secret.')
                    ->defaultNull()
                ->end()
                ->scalarNode('api_url')
                    ->info('The Personio API URL.')
                    ->defaultValue('https://api.personio.de/v1/')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
