<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Models\Currency as CurrencyModel;
use Ilch\Validation;

class Currency extends \Ilch\Controller\Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuNote',
                'active' => false,
                'icon' => 'fas fa-info-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'note', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[4][0]['active'] = true;
        } else {
            $items[4]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuCurrencies',
            $items
        );
    }

    public function indexAction()
    {
        $currencyMapper = new CurrencyMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCurrencies'), ['action' => 'index']);

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_currencies')) {
                foreach ($this->getRequest()->getPost('check_currencies') as $id) {
                    if ($currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('shop_currency')) {
                        $this->addMessage('currencyInUse', 'danger');
                        continue;
                    }
                    $currencyMapper->deleteCurrencyById($id);
                }
            }
        }

        $this->getView()->set('currencies', $currencyMapper->getCurrencies());
    }

    public function treatAction()
    {
        $currencyMapper = new CurrencyMapper();
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCurrencies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCurrencies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat', 'id' => 'treat']);
        }

        $post = [
            'id' => '',
            'name' => ''
        ];

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            $post = [
                'id' => $this->getRequest()->getPost('id'),
                'name' => trim($this->getRequest()->getPost('name'))
            ];

            if (!$this->getRequest()->getParam('id')) {
                $idValidators = 'numeric|integer|min:1';
            }

            $validation = Validation::create($post, [
                'id' => 'required|numeric|integer|min:1',
                'name' => 'required'
            ]);

            if ($validation->isValid()) {
                if ($currencyMapper->currencyWithNameExists($post['name'])) {
                    $this->addMessage('alreadyExisting', 'danger');
                } else {
                    $currencyModel = new CurrencyModel();
                    $currencyModel->setId($post['id']);
                    $currencyModel->setName($post['name']);
                    
                    $currencyMapper->save($currencyModel);
                    $this->addMessage('saveSuccess');
                    $this->redirect(['action' => 'index']);
                }
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $currency = $currencyMapper->getCurrencyById($id);
        if (count($currency) > 0) {
            $currency = $currency[0];
        } else {
            $currency = new CurrencyModel();
        }

        $this->getView()->set('currency', $currency);
    }

    public function deleteAction()
    {
        if ($this->getRequest() && $this->getRequest()->isSecure()) {
            $currencyMapper = new CurrencyMapper();
            $id = $this->getRequest()->getParam('id');
            
            if ($currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('checkoutbasic_currency')) {
                $this->addMessage('currencyInUse', 'danger');
                $this->redirect(['action' => 'index']);
            }

            $currencyMapper->deleteCurrencyById($id);
            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }

}
