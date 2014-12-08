<?php
/**
 * User: kay
 * Date: 08.12.14
 * Time: 19:50
 */

namespace Hn\VfsmBundle\StateMachine;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Specification implements ConfigurationInterface {

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $condition = $treeBuilder->root('condition');
        $condition
            ->prototype('array')
                ->prototype('scalar')
                ->end()
            ->end();

        $treeBuilder = new TreeBuilder();
        $inputActions = $treeBuilder->root('input_actions');
        $inputActions
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('action')->isRequired()->end()
                    ->append($condition)
                ->end()
            ->end();

        $treeBuilder = new TreeBuilder();
        $transitions = $treeBuilder->root('transitions');
        $transitions
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('to_state')->isRequired()->end()
                    ->scalarNode('action')->end()
                    ->append($condition)
                ->end()
            ->end();

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('states');
        $rootNode
            ->useAttributeAsKey('state')
            ->prototype('array')
                ->children()
                    ->scalarNode('enter_action')->defaultNull()->end()
                    ->scalarNode('exit_action')->defaultNull()->end()
                    ->append($inputActions)
                    ->append($transitions)
                ->end()
            ->end();


        return $treeBuilder;
    }
}