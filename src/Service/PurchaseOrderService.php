<?php
/**
 * Created by PhpStorm.
 * User: Bassem
 * Date: 14/03/2019
 * Time: 22:07
 */

namespace Odoo\ConnectorBundle\Service;

class PurchaseOrderService
{
    private $odoo;
    private $product;

    /**
     * PurchaseOrderService constructor.
     * @param $odoo
     */
    public function __construct($odoo, $product)
    {
        $this->odoo = $odoo;
        $this->product = $product;
    }

    /**
     * Create a purchase order with commande lines
     */
    public function addPurchase($data)
    {

        $idVendor = (int)$data['vendor'];
        $vendor = $this->odoo->search('res.partner');
        $date_planned = date('Y-m-d H:i:s', strtotime($data['planned_date']));
        $purchase = array(
            'company_id' => $vendor[0]['company_id'][0],
            'currency_id' => $vendor[0]['currency_id'][0],
            'partner_id' => $idVendor,
            'partner_ref' => (String)$data['partner_ref'],
            'date_order' => $date_planned

        );
        $id = $this->odoo->create('purchase.order', $purchase);
        if (isset($data['article'])) {
            $this->addCommandeLine($data, $id);
        }
    }

    /**
     * Create a commande line
     */
    public function addCommandeLine($data, $id)
    {

        for ($i = 0; $i < count($data['article']); $i++) {
            $date_planned = date('Y-m-d H:i:s', strtotime($data['date'][$i]));
            if (strlen($data["description"][$i]) == 0) {
                $req["description"][$i] = $this->product->getProduct($data["article"][$i])['display_name'];
            }
            $commandeline = array(
                'name' => $data["description"][$i],
                'date_planned' => $date_planned,
                'product_qty' => $data["quantite"][$i],
                'product_id' => $data["article"][$i],
                'order_id' => $id,
                'price_unit' => $data["pu"][$i],
                'product_uom' => 2,
                // 'taxes_id'=>$req["taxe"][$i],

            );
            $this->odoo->create('purchase.order.line', $commandeline);

        }

    }

    /**
     * return a purchase order
     */
    public function getPurchase($id)
    {
        $id = (int)$id;
        $option[0] = array('id', '=', $id);
        $result = $this->odoo->search('purchase.order', $option);
        return $result;
    }

    /**
     * return a commande line
     */
    public function getCommandeLine($id)
    {
        $id = (int)$id;
        $option[0] = array('id', '=', $id);
        $result = $this->odoo->search('purchase.order.line', $option);
        return $result[0];
    }

    public function getPurchaseOrderCommandeLine($id)
    {
        $pruchase = $this->getPurchase($id);
        $tab = array();
        foreach ($pruchase[0]['order_line'] as $value) {
            array_push($tab, $this->getCommandeLine($value));
        }
        return $tab;
    }

    /**
     * return a taxe
     */
    public function geTaxes($id)
    {
        $id = (int)$id;
        $option[0] = array('id', '=', $id);
        return $this->odoo->search('account.tax', $option)[0];

    }
    /**
     * update commande line
     */
    public function updateCommandeLine($id, $option)
    {
        $id = (int)$id;
        $this->odoo->update('purchase.order.line', $id, $option);


    }

    /**
     * update  purchase order
     */
    public function updatePurchase($id, $data)
    {
        $date_planned = date('Y-m-d H:i:s', strtotime($data['planned_date']));

        $option = array(
            'partner_id' => $data['vendor'],
            'date_order' => $date_planned,
            'partner_ref' => (String)$data['partner_ref']
        );

        $this->odoo->update('purchase.order', $id, $option);
    }

    /**
     * return a purchase order with commande lines
     */
    public function getPurchaseOrder($id)
    {
        $commandeLines = $this->getPurchaseOrderCommandeLine($id);
        $result = array();
        foreach ($commandeLines as $cl) {

            if (count($cl['taxes_id']) > 0) {
                $cl['taxes'] = array();
                foreach ($cl['taxes_id'] as $taxe) {
                    $opt[0] = array('id', '=', $taxe);
                    array_push($cl['taxes'], $this->odoo->search('account.tax', $opt)[0]['name']);
                }
            }
            array_push($result, $cl);
        }
        return $result;
    }

    /**
     * update purchase with commande lines
     */
    public function updatePurchaseOrder($data)
    {

        $this->updatePurchase($data['purchase_id'], $data);
        $commandeLine = $this->getPurchase($data["purchase_id"])[0]['order_line'];
        for ($i = 0; $i < count($commandeLine); $i++) {
            $this->odoo->delete('purchase.order.line', $commandeLine[$i]);
        }

        if (isset($data['article'])) {
            $this->addCommandeLine($data, $data['purchase_id']);
        }
    }


}