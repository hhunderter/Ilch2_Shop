<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Costumer as CostumerMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;

class Costumerarea extends Frontend
{
    public function indexAction()
    {
        $ordersMapper = new OrdersMapper();
        $costumerMapper = new CostumerMapper();
        $orders = [];

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumerArea'), ['controller' => 'costumerarea', 'action' => 'index']);

        if ($this->getUser()) {
            $costumer = $costumerMapper->getCostumerByUserId($this->getUser()->getId());

            if ($costumer) {
                $orders = $ordersMapper->getOrdersByCostumerId($costumer->getId());
            }
        } else {
            $this->addMessage('loginRequiredCostumerArea', 'danger');
            $this->redirect(['module' => 'user', 'controller' => 'login', 'action' => 'index']);
        }

        $this->getView()->set('orders', $orders);
    }

    public function showAction()
    {
        $currencyMapper = new CurrencyMapper();
        $ordersMapper = new OrdersMapper();
        $costumerMapper = new CostumerMapper();
        $itemsMapper = new ItemsMapper();

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];
        $order = [];

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumerArea'), ['controller' => 'costumerarea', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumerAreaOrderDetails'), ['controller' => 'costumerarea', 'action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        if ($this->getUser()) {
            $costumer = $costumerMapper->getCostumerByUserId($this->getUser()->getId());

            if ($costumer) {
                $order = $ordersMapper->getOrders(['o.id' => $this->getRequest()->getParam('id'), 'o.costumerId' => $costumer->getId()]);
                $order = (!empty($order)) ? $order[0] : [];
            }
        } else {
            $this->addMessage('loginRequiredCostumerArea', 'danger');
            $this->redirect(['module' => 'user', 'controller' => 'login', 'action' => 'index']);
        }

        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('order', $order);
        $this->getView()->set('itemsMapper', $itemsMapper);
    }

    public function downloadAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        set_time_limit(0);
        $shopInvoicePath = ROOT_PATH.'/application/modules/shop/static/invoice/';

        $id = $this->getRequest()->getParam('id');

        if (!empty($id) && is_numeric($id)) {
            $ordersMapper = new OrdersMapper();
            $order = $ordersMapper->getOrderById($id);

            if ($order !== null) {
                $fullPath = $shopInvoicePath.$order->getInvoiceFilename().'.pdf';
                if ($fd = fopen($fullPath, 'rb')) {
                    $path_parts = pathinfo($fullPath);
                    // Remove the random part of the filename as it should not end in e.g. the browser history.
                    $publicFileName = preg_replace('/_[^_.]*\./', '.', $path_parts['basename']);

                    header('Content-type: application/pdf');
                    header('Content-Disposition: filename="' .$publicFileName. '"');
                    header('Content-length: ' .filesize($fullPath));
                    // RFC2616 section 14.9.1: Indicates that all or part of the response message is intended for a single user and MUST NOT be cached by a shared cache, such as a proxy server.
                    header('Cache-control: private');
                    while(!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                } else {
                    $this->addMessage('invoiceNotFound', 'danger');
                }
                fclose($fd);
            }
        } else {
            $this->addMessage('invoiceNotFound', 'danger');
        }

        $this->redirect(['controller' => 'costumerarea', 'action' => 'show', 'id' => $id]);
    }
}
