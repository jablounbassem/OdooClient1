<?php
/**
 * Created by PhpStorm.
 * User: Bassem
 * Date: 25/04/2019
 * Time: 14:51
 */

namespace Odoo\ConnectorBundle;

use Odoo\ConnectorBundle\DependencyInjection\OdooServiceExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
class OdooConnectorBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new OdooServiceExtension();
        }
        return $this->extension;
    }
}
