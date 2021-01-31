<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Modules\Shop\Mappers\Category as CategoryMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Models\Orders as OrdersModel;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $userMapper = new UserMapper();

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];
        $categories = $categoryMapper->getCategories();
        
        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $adminAccess = null;
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }

        if ($this->getRequest()->getParam('catId')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));
            
            if (!$category) {
                $this->redirect(['action' => 'index']);
            }
            
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);

            if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
                $this->getLayout()->header()->css('static/css/style_front.css');
                $this->getLayout()->getHmenu()
                    ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);
            } else {
                $this->redirect(['action' => 'index']);
            }

            $shopItems = $itemsMapper->getShopItems(['cat_id' => $this->getRequest()->getParam('catId'), 'status' => 1]);
        } else {
            $firstAllowedCategory = null;

            foreach ($categories as $category) {
                if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
                    $firstAllowedCategory = $category;
                    break;
                }
            }

            if ($firstAllowedCategory !== null) {
                $this->getLayout()->header()->css('static/css/style_front.css');
                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($firstAllowedCategory->getTitle(), ['action' => 'index', 'catId' => $firstAllowedCategory->getId()]);
                $shopItems = $itemsMapper->getShopItems(['cat_id' => $firstAllowedCategory->getId(), 'status' => 1]);
                $this->getView()->set('firstCatId', $firstAllowedCategory->getId());
            } else {
                $this->getLayout()->header()->css('static/css/style_front.css');
                $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);
                $shopItems = $itemsMapper->getShopItems(['status' => 1]);
            }
        }
        
        $this->getView()->set('adminAccess', $adminAccess);
        $this->getView()->set('categories', $categories);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('shopItems', $shopItems);
    }

    public function cartAction()
    {
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCart'), ['action' => 'cart']);

        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
    }

    public function agbAction()
    {
        $settingsMapper = new SettingsMapper();
        
        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuAGB'), ['action' => 'agb']);

        $this->getView()->set('shopSettings', $settingsMapper->getSettings());
    }

    public function orderAction()
    {
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $ordersMapper = new OrdersMapper;
        $ilchDate = new \Ilch\Date;
        $captchaNeeded = captchaNeeded();
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCart'), ['action' => 'cart'])
            ->add($this->getTranslator()->trans('menuOrder'), ['action' => 'order']);

            if ($this->getRequest()->getPost('saveOrder')) {
            $validationRules = [
                'prename' => 'required',
                'lastname' => 'required',
                'street' => 'required',
                'postcode' => 'required|numeric',
                'city' => 'required',
                'acceptOrder' =>  'required'
            ];

            if ($captchaNeeded) {
                $validationRules['captcha'] = 'captcha';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);
            if ($validation->isValid()) {
                $model = new OrdersModel();
                $model->setDatetime($ilchDate->toDb());
                $model->setPrename($this->getRequest()->getPost('prename'));
                $model->setLastname($this->getRequest()->getPost('lastname'));
                $model->setStreet($this->getRequest()->getPost('street'));
                $model->setPostcode($this->getRequest()->getPost('postcode'));
                $model->setCity($this->getRequest()->getPost('city'));
                $model->setCountry($this->getRequest()->getPost('country'));
                $model->setEmail($this->getRequest()->getPost('email'));
                $model->setOrder($this->getRequest()->getPost('order'));
                $ordersMapper->save($model);
                
                $arrayOrder = $this->getRequest()->getPost('order');
                $arrayOrder = json_decode(str_replace("'", '"', $arrayOrder), true);
                foreach ($arrayOrder as $product) {
                    $itemsMapper->updateStock($product["id"], $product["quantity"]);
                }
                $this->redirect()
                    ->to(['action' => 'success']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'order']);
            }
        }

        $this->getView()->set('captchaNeeded', $captchaNeeded);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
    }
    
    public function successAction()
    {
        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);
    }

    public function showAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $userMapper = new UserMapper();
        
        $shopItem = $itemsMapper->getShopById($this->getRequest()->getParam('id'));
        
        if (empty($shopItem) || $shopItem->getStatus() != 1) {
            $this->redirect(['action' => 'index']);
        }
        
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];
        $category = $categoryMapper->getCategoryById($shopItem->getCatId());
        $adminAccess = null;

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
        }
        
        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);

        if ($adminAccess === true || is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getHmenu()
                ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()])
                ->add($shopItem->getName(), ['action' => 'show', 'id' => $shopItem->getId()]);
        } else {
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('shopItem', $shopItem);
        $this->getView()->set('currency', $currency->getName());
    }

}
