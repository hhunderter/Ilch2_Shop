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
                'name' => 'menuOverwiev',
                'active' => false,
                'icon' => 'fas fa-store-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuItems',
                'active' => false,
                'icon' => 'fas fa-tshirt',
                'url' => $this->getLayout()->getUrl(['controller' => 'items', 'action' => 'index'])
            ],
            [
                'name' => 'menuCostumers',
                'active' => true,
                'icon' => 'fas fa-users',
                'url' => $this->getLayout()->getUrl(['controller' => 'costumers', 'action' => 'index'])
            ],
            [
                'name' => 'menuOrders',
                'active' => false,
                'icon' => 'fas fa-cart-arrow-down',
                'url' => $this->getLayout()->getUrl(['controller' => 'orders', 'action' => 'index'])
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fas fa-list-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'menuCurrencies',
                'active' => false,
                'icon' => 'fas fa-money-bill-alt',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fas fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuNote',
                'active' => false,
                'icon' => 'fas fa-info-circle',
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

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumers'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCostumer'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        $costumer = $costumerMapper->getCostumerById($this->getRequest()->getParam('id'));

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
        if ($this->getRequest()->isSecure()) {
            $costumerMapper = new CostumerMapper();

            $costumerMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        } else {
            $this->addMessage('deleteCostumerFailed', 'danger');
        }

        $this->redirect(['action' => 'index']);
    }
}
