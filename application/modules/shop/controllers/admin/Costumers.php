<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\User\Mappers\User as UserMapper;
use Modules\Shop\Mappers\Costumer as CostumerMapper;
use Modules\Shop\Mappers\Address as AddressMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;

class Costumers extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuOverview',
                'active' => false,
                'icon' => 'fa-solid fa-shop',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuItems',
                'active' => false,
                'icon' => 'fa-solid fa-tshirt',
                'url' => $this->getLayout()->getUrl(['controller' => 'items', 'action' => 'index'])
            ],
            [
                'name' => 'menuCostumers',
                'active' => true,
                'icon' => 'fa-solid fa-users',
                'url' => $this->getLayout()->getUrl(['controller' => 'costumers', 'action' => 'index'])
            ],
            [
                'name' => 'menuOrders',
                'active' => false,
                'icon' => 'fa-solid fa-cart-arrow-down',
                'url' => $this->getLayout()->getUrl(['controller' => 'orders', 'action' => 'index'])
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa-solid fa-rectangle-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'menuCurrencies',
                'active' => false,
                'icon' => 'fa-solid fa-money-bill-1',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuNote',
                'active' => false,
                'icon' => 'fa-solid fa-circle-info',
                'url' => $this->getLayout()->getUrl(['controller' => 'note', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuCostumers',
            $items
        );
    }

    public function indexAction()
    {
        $costumerMapper = new CostumerMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumers'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_costumers') as $costumerId) {
                $costumerMapper->delete($costumerId);
            }

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('costumers', $costumerMapper->getCostumers());
    }

    public function showAction()
    {
        $costumerMapper = new CostumerMapper();
        $userMapper = new UserMapper();
        $addressMapper = new AddressMapper();
        $ordersMapper = new OrdersMapper();
        $costumer = null;

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumers'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumer'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        if ($this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $costumer = $costumerMapper->getCostumerById($this->getRequest()->getParam('id'));
        }

        if (!$costumer) {
            $this->addMessage('costumerNotFound', 'danger');
            $this->redirect(['action' => 'index']);
        }

        $user = $userMapper->getUserById($costumer->getUserId());

        $this->getView()->set('costumer', $costumer);
        $this->getView()->set('costumerUsername', (!empty($user) ? $user->getName() : ''));
        $this->getView()->set('addresses', $addressMapper->getAddressesByCostumerId($this->getRequest()->getParam('id')));
        $this->getView()->set('orders', $ordersMapper->getOrdersByCostumerId($this->getRequest()->getParam('id')));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $costumerMapper = new CostumerMapper();

            $costumerMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        } else {
            $this->addMessage('deleteCostumerFailed', 'danger');
        }

        $this->redirect(['action' => 'index']);
    }
}
