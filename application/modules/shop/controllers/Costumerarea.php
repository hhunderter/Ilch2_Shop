<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
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
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
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
        $ordersMapper = new OrdersMapper();
        $costumerMapper = new CostumerMapper();
        $itemsMapper = new ItemsMapper();
        $order = [];

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
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

        $this->getView()->set('order', $order);
        $this->getView()->set('itemsMapper', $itemsMapper);
    }
}
