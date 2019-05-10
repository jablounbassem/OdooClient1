<?php
/**
 * Created by PhpStorm.
 * User: Bassem
 * Date: 22/04/2019
 * Time: 10:43 PM
 */

namespace Odoo\ConnectorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OdooServiceExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $load= new XmlFileLoader($container,new FileLocator(__DIR__ . '/../Resources/config'));
        $load->load('services.xml');
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

    }
    public function getAlias()
    {
        return 'app_controller_form';
    }
}
