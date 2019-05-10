<?php

namespace Odoo\ConnectorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends Controller
{
    public function indexAction()
    {
        return $this->render('OdooConnectorBundle::index.html.twig');
    }

    public function listPurchaseAction()
    {
        $odooService = $this->get('odoo_service');
        $purchases = $odooService->search('purchase.order');
        return $this->render('@OdooConnector/Purchase/list.html.twig', array(
            "purchases" => $purchases
        ));
    }

    public function addPurchaseFormAction()
    {
        $odooService = $this->get('odoo_service');
        $option[0] = array('supplier', '=', true);
        $vendor = $odooService->search('res.partner', $option);
        $articles = $odooService->search('product.product');
        $opt[0] = array('type_tax_use', '=', 'purchase');
        $taxes = $odooService->search('account.tax', $opt);
        return $this->render('@OdooConnector/Purchase/add.html.twig', array(
            "vendors" => $vendor,
            "articles" => $articles,
            "taxes" => $taxes,
        ));
    }

    public function addPurchaseAction(Request $request)
    {
        $purchaseOrderService = $this->get('purchase_order_service');
        $data = $request->request->all();
        $purchaseOrderService->addPurchase($data);
        return $this->forward('OdooConnectorBundle:Purchase:listPurchase');

    }

    public function getPurchaseAction($id)
    {
        $purchaseOrderService = $this->get('purchase_order_service');
        $purchase = $purchaseOrderService->getPurchase($id);
        if (count($purchase) == 0) {
            return $this->redirect($this->generateUrl('odoo_connector_purchaseOrderLists'));
        } else {
            $commandeLines = $purchaseOrderService->getPurchaseOrder($id);
        }
        return $this->render("@OdooConnector/Purchase/purchase.html.twig", array(
            'purchase' => $purchase[0],
            'commandeLines' => $commandeLines
        ));


    }

    public function updatePurchaseFormAction($id)
    {
        $odooService = $this->get('odoo_service');
        $purchaseOrderService = $this->get('purchase_order_service');
        $purchase = $purchaseOrderService->getPurchase($id);
        if (count($purchase) == 0) {
            return $this->redirect($this->generateUrl('odoo_connector_purchaseOrderLists'));
        } else {

            $commandeLines = $purchaseOrderService->getPurchaseOrderCommandeLine($id);
            $option[0] = array('supplier', '=', true);
            $vendors = $odooService->search('res.partner', $option);
            $articles = $odooService->search('product.product');
            $opt[0] = array('type_tax_use', '=', 'purchase');
            $taxes = $odooService->search('account.tax', $opt);
            return $this->render("@OdooConnector/Purchase/update.html.twig", array(
                'purchase' => $purchase[0],
                'vendors' => $vendors,
                'commandeLines' => $commandeLines,
                "articles" => $articles,
                "taxes" => $taxes,
            ));
        }

    }

    public function updatePurchaseAction(Request $request)
    {

        $purchaseService = $this->get('purchase_order_service');
        $odooService = $this->get('odoo_service');
        $data = $request->request->all();
        if (isset($data['purchase_id'])) {
            // update purchase
            $purchaseService->updatePurchaseOrder($data);
            return $this->redirectToRoute('odoo_connector_getPurchase', ['id' => $data['purchase_id']]);

        }

        return $this->forward('OdooConnectorBundle:Purchase:listPurchase');


    }

    public function getProvidersAction()
    {
        $odooService = $this->get('odoo_service');
        $option[0] = array('is_company', '=', true);
        $option[1] = array('supplier', '=', true);
        $providers = $odooService->search('res.partner', $option);
        $tab = array();
        for ($i = 0; $i < count($providers); $i++) {
            if (count($providers[$i]['category_id']) > 0) {
                for ($j = 0; $j < count($providers[$i]['category_id']); $j++) {
                    $id = (int)$providers[$i]['category_id'][$j];
                    $opt[0] = array('id', '=', $id);
                    $cat = $odooService->search('res.partner.category', $opt)[0]['display_name'];
                    $providers[$i]['categories'][$j] = $cat;
                }
            }

            // array_push($tab, $cl);
        }
        //dump($providers).die();
        //dump($odooService->fields('res.partner.category')).die();
        return $this->render('OdooConnectorBundle:Contact:Provider.html.twig',
            array(
                "providers" => $providers
            )
        );
    }


}
