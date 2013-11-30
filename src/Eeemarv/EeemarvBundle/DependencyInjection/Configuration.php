<?php

namespace Eeemarv\EeemarvBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('eeemarv');
        
        $rootNode->children()
 			->scalarNode('code')->isRequired()->end()
			->arrayNode('locales')->requiresAtLeastOneElement()->isRequired()->prototype('scalar')->isRequired()->end()->end() 
			->scalarNode('currency_rate')->isRequired()->info('divisor for lets-seconds (3600 per hour) to get local currency')->end()
			->arrayNode('mail')->children()
				->scalarNode('info')->defaultValue('info')->end()
				->scalarNode('no-reply')->defaultValue('no-reply')->end()
				->scalarNode('list')->defaultValue('list')->end()
				->scalarNode('bounce')->defaultValue('bounce')->end()
				->end()
			;
        return $treeBuilder;
    }
}
