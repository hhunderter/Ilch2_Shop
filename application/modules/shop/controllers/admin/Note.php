<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;

class Note extends Admin
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
                'active' => false,
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
                'active' => true,
                'icon' => 'fas fa-info-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'note', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuNote',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuNote'), ['action' => 'note']);
    }

}
