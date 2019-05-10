<?php
/**
 * Created by PhpStorm.
 * User: safa
 * Date: 31/03/2019
 * Time: 12:14 AM
 */

namespace Odoo\ConnectorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_controller_form');

        return $treeBuilder;
    }
}