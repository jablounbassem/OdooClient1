<?php
/**
 * Created by PhpStorm.
 * User: Bassem
 * Date: 27/03/2019
 * Time: 09:55
 */

namespace Odoo\ConnectorBundle\Service;


class ProductService
{
    private $odoo;

    /**
     * ProductService constructor.
     * @param $odoo
     */
    public function __construct($odoo)
    {
        $this->odoo = $odoo;
    }

    public function getProduct($id)
    {
        $id=(int)$id;
        $option[0] = array('id', '=',$id);
        return $this->odoo->search('product.product',$option)[0];

    }
}