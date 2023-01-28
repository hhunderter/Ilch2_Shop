<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Date;
use Ilch\Mail;
use Modules\Shop\Mappers\Address as AddressMapper;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Shop\Mappers\Category as CategoryMapper;
use Modules\Shop\Mappers\Costumer as CostumerMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Models\Order as OrdersModel;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends Frontend
{
    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $userMapper = new UserMapper();

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];

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

        $categories = $categoryMapper->getCategoriesByAccess($readAccess);

        if ($this->getRequest()->getParam('catId')) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));

            if (!$category) {
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);

            if (is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
                $this->getLayout()->header()->css('static/css/style_front.css');
                $this->getLayout()->getHmenu()
                    ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);
            } else {
                $this->redirect(['action' => 'index']);
            }

            $shopItems = $itemsMapper->getShopItems(['cat_id' => $this->getRequest()->getParam('catId'), 'status' => 1]);
        } elseif (!empty($categories)) {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                ->add($categories[0]->getTitle(), ['action' => 'index', 'catId' => $categories[0]->getId()]);
            $shopItems = $itemsMapper->getShopItems(['cat_id' => $categories[0]->getId(), 'status' => 1]);
            $this->getView()->set('firstCatId', $categories[0]->getId());
        } else {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);
            $shopItems = $itemsMapper->getShopItems(['status' => 1]);
        }

        $this->getView()->set('categories', $categories);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
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
        $addressMapper = new AddressMapper();
        $emailsMapper = new EmailsMapper();
        $costumerMapper = new CostumerMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $ordersMapper = new OrdersMapper();
        $settingsMapper = new SettingsMapper();
        $ilchDate = new Date();
        $captchaNeeded = captchaNeeded();
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];
        $addresses = [];
        $costumer = null;

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCart'), ['action' => 'cart'])
            ->add($this->getTranslator()->trans('menuOrder'), ['action' => 'order']);

        if (!empty($this->getUser())) {
            $costumer = $costumerMapper->getCostumerByUserId($this->getUser()->getId());
        }

        if (!empty($costumer)) {
            $addresses = $addressMapper->getAddressesByCostumerId($costumer->getId());
        }

        if ($this->getRequest()->getPost('saveOrder')) {
            $validationRules = [
                'prename' => 'required',
                'lastname' => 'required',
                'street' => 'required',
                'postcode' => 'required|numeric|integer',
                'city' => 'required',
                'acceptOrder' =>  'required'
            ];

            if ($this->getRequest()->getPost('dropdownDeliveryAddress')) {
                $validationRules = ['dropdownDeliveryAddress' => 'required|numeric'];
            }

            if ($this->getRequest()->getPost('differentInvoiceAddress')) {
                if ($this->getRequest()->getPost('dropdownInvoiceAddress')) {
                    $validationRules['dropdownInvoiceAddress'] = 'required|numeric';
                } else {
                    $validationRules['invoiceAddressPrename'] = 'required';
                    $validationRules['invoiceAddressLastname'] = 'required';
                    $validationRules['invoiceAddressStreet'] = 'required';
                    $validationRules['invoiceAddressPostcode'] = 'required|numeric|integer';
                    $validationRules['invoiceAddressCity'] = 'required';
                }
            }

            if ($captchaNeeded) {
                $validationRules['captcha'] = 'captcha';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);
            if ($validation->isValid()) {
                $model = new OrdersModel();
                $model->setDatetime($ilchDate->toDb());

                if (!empty($costumer)) {
                    $addresses = $addressMapper->getAddressesByCostumerId($costumer->getId());

                    $model->setCostumerId($costumer->getId());
                    $model->getDeliveryAddress()->setCostumerID($costumer->getId());
                    $model->getInvoiceAddress()->setCostumerID($costumer->getId());
                }

                if ($this->getRequest()->getPost('dropdownDeliveryAddress')) {
                    // Don't use possible user input. Get the address from the database.
                    if (empty($costumer)) {
                        // Pretends to select a known address of him, but isn't a costumer? Redirect with error message.
                        $this->addMessage('unknownCostumer', 'danger');
                        $this->redirect()
                            ->to(['action' => 'order']);
                    }
                    $address = $addressMapper->getAddresses(['id' => $this->getRequest()->getPost('dropdownDeliveryAddress'), 'costumerId' => $costumer->getId()])[0];

                    $model->getDeliveryAddress()->setId($address->getId());
                    $model->getDeliveryAddress()->setPrename($address->getPrename());
                    $model->getDeliveryAddress()->setLastname($address->getLastname());
                    $model->getDeliveryAddress()->setStreet($address->getStreet());
                    $model->getDeliveryAddress()->setPostcode($address->getPostcode());
                    $model->getDeliveryAddress()->setCity($address->getCity());
                    $model->getDeliveryAddress()->setCountry($address->getCountry());
                } else {
                    $model->getDeliveryAddress()->setPrename($this->getRequest()->getPost('prename'));
                    $model->getDeliveryAddress()->setLastname($this->getRequest()->getPost('lastname'));
                    $model->getDeliveryAddress()->setStreet($this->getRequest()->getPost('street'));
                    $model->getDeliveryAddress()->setPostcode($this->getRequest()->getPost('postcode'));
                    $model->getDeliveryAddress()->setCity($this->getRequest()->getPost('city'));
                    $model->getDeliveryAddress()->setCountry($this->getRequest()->getPost('country'));
                }

                $model->setEmail($this->getRequest()->getPost('email'));
                $model->setOrder($this->getRequest()->getPost('order'));

                if ($this->getRequest()->getPost('differentInvoiceAddress')) {

                    if ($this->getRequest()->getPost('dropdownInvoiceAddress')) {
                        // Don't use possible user input. Get the address from the database.
                        if (empty($costumer)) {
                            // Pretends to select a known address of him, but isn't a costumer? Redirect with error message.
                            $this->addMessage('unknownCostumer', 'danger');
                            $this->redirect()
                                ->to(['action' => 'order']);
                        }
                        $address = $addressMapper->getAddresses(['id' => $this->getRequest()->getPost('dropdownInvoiceAddress'), 'costumerId' => $costumer->getId()])[0];

                        $model->getInvoiceAddress()->setId($address->getId());
                        $model->getInvoiceAddress()->setPrename($address->getPrename());
                        $model->getInvoiceAddress()->setLastname($address->getLastname());
                        $model->getInvoiceAddress()->setStreet($address->getStreet());
                        $model->getInvoiceAddress()->setPostcode($address->getPostcode());
                        $model->getInvoiceAddress()->setCity($address->getCity());
                        $model->getInvoiceAddress()->setCountry($address->getCountry());
                    } else {
                        $model->getInvoiceAddress()->setPrename($this->getRequest()->getPost('invoiceAddressPrename'));
                        $model->getInvoiceAddress()->setLastname($this->getRequest()->getPost('invoiceAddressLastname'));
                        $model->getInvoiceAddress()->setStreet($this->getRequest()->getPost('invoiceAddressStreet'));
                        $model->getInvoiceAddress()->setPostcode($this->getRequest()->getPost('invoiceAddressPostcode'));
                        $model->getInvoiceAddress()->setCity($this->getRequest()->getPost('invoiceAddressCity'));
                        $model->getInvoiceAddress()->setCountry($this->getRequest()->getPost('invoiceAddressCountry'));
                    }
                } else {
                    $model->setInvoiceAddress($model->getDeliveryAddress());
                }

                $ordersMapper->save($model);

                $arrayOrder = $this->getRequest()->getPost('order');
                $arrayOrder = json_decode(str_replace("'", '"', $arrayOrder), true);
                foreach ($arrayOrder as $product) {
                    $itemsMapper->removeStock($product['id'], $product['quantity']);
                }

                // Send confirmation email.
                $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                $date = new Date();
                $mailContent = $emailsMapper->getEmail('shop', 'order_confirmed_mail', $this->getTranslator()->getLocale());
                $name = $this->getLayout()->escape($model->getDeliveryAddress()->getLastname());

                $layout = $_SESSION['layout'] ?? '';

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/shop/layouts/mail/orderconfirmed.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/shop/layouts/mail/orderconfirmed.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/shop/layouts/mail/orderconfirmed.php');
                }
                $messageReplace = [
                    '{content}' => $this->getLayout()->purify($mailContent->getText()),
                    '{shopname}' => $this->getLayout()->escape($settingsMapper->getSettings()->getShopName()),
                    '{date}' => $date->format('l, d. F Y', true),
                    '{name}' => $name,
                    '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new Mail();
                $mail->setFromName($siteTitle)
                    ->setFromEmail($this->getConfig()->get('standardMail'))
                    ->setToName($name)
                    ->setToEmail($this->getRequest()->getPost('email'))
                    ->setSubject($this->getLayout()->purify($mailContent->getDesc()))
                    ->setMessage($message)
                    ->send();

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

        $this->getView()->set('addresses', $addresses);
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

        $shopItem = $itemsMapper->getShopItemById($this->getRequest()->getParam('id'));

        if (empty($shopItem) || $shopItem->getStatus() != 1) {
            $this->redirect(['action' => 'index']);
        }

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];
        $category = $categoryMapper->getCategoryById($shopItem->getCatId());

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

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);

        if (is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
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
