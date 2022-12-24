<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Models\Settings as SettingsModel;
use Ilch\Validation;

class Settings extends Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
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
        
        $items[5]['active'] = true;

        $this->getLayout()->addMenu
        (
            'menuSettings',
            $items
        );
    }

    public function indexAction()
    {
        $settingsMapper = new SettingsMapper();
        
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettingShop'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'shopName' => 'required',
                'shopStreet' => 'required',
                'shopPlz' => 'required',
                'shopCity' => 'required',
                'shopTel' => 'required',
                'shopMail' => 'required',
                'shopWeb' => 'required',
                'shopStNr' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new SettingsModel();
                $model->setShopName($this->getRequest()->getPost('shopName'));
                $model->setShopStreet($this->getRequest()->getPost('shopStreet'));
                $model->setShopPlz($this->getRequest()->getPost('shopPlz'));
                $model->setShopCity($this->getRequest()->getPost('shopCity'));
                $model->setShopLogo($this->getRequest()->getPost('shopLogo'));
                $model->setShopTel($this->getRequest()->getPost('shopTel'));
                $model->setShopFax($this->getRequest()->getPost('shopFax'));
                $model->setShopMail($this->getRequest()->getPost('shopMail'));
                $model->setShopWeb($this->getRequest()->getPost('shopWeb'));
                $model->setShopStNr($this->getRequest()->getPost('shopStNr'));
                $settingsMapper->updateSettingShop($model);
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('settings', $settingsMapper->getSettings());
    }
    
    public function bankAction()
    {
        $settingsMapper = new SettingsMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettingBank'), ['action' => 'bank']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'bankName' => 'required',
                'bankOwner' => 'required',
                'bankIBAN' => 'required',
                'bankBIC' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new SettingsModel();
                $model->setBankName($this->getRequest()->getPost('bankName'));
                $model->setBankOwner($this->getRequest()->getPost('bankOwner'));
                $model->setBankIBAN($this->getRequest()->getPost('bankIBAN'));
                $model->setBankBIC($this->getRequest()->getPost('bankBIC'));
                $settingsMapper->updateSettingBank($model);
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'bank']);
            }
        }

        $this->getView()->set('settings', $settingsMapper->getSettings());
    }
    
    public function defaultAction()
    {
        $currencyMapper = new CurrencyMapper();
        $settingsMapper = new SettingsMapper();
        
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettingDefault'), ['action' => 'default']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'fixTax' => 'required|numeric|integer|min:1',
                'fixShippingCosts' => 'required',
                'fixShippingTime' => 'required|numeric|integer|min:1',
                'invoiceTextTop' => 'required',
                'invoiceTextBottom' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new SettingsModel();
                $model->setFixTax($this->getRequest()->getPost('fixTax'));
                $model->setFixShippingCosts($this->getRequest()->getPost('fixShippingCosts'));
                $model->setFixShippingTime($this->getRequest()->getPost('fixShippingTime'));
                $model->setInvoiceTextTop($this->getRequest()->getPost('invoiceTextTop'));
                $model->setInvoiceTextBottom($this->getRequest()->getPost('invoiceTextBottom'));
                $settingsMapper->updateSettingDefault($model);
                $this->getConfig()->set('shop_currency', $this->getRequest()->getPost('shopCurrency'));
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'default']);
            }
        }

        $this->getView()->set('settings', $settingsMapper->getSettings());
        $this->getView()->set('currencies', $currencyMapper->getCurrencies());
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('shopCurrency', $this->getConfig()->get('shop_currency'));
    }
    
    public function agbAction()
    {
        $settingsMapper = new SettingsMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettingAGB'), ['action' => 'agb']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'settingsAGB' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new SettingsModel();
                $model->setAGB($this->getRequest()->getPost('settingsAGB'));
                $settingsMapper->updateSettingAGB($model);
                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'agb']);
            }
        }

        $this->getView()->set('settings', $settingsMapper->getSettings());
    }

}
