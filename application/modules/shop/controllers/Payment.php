<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;

class Payment extends Frontend
{
    public function indexAction()
    {
        $settingsMapper = new SettingsMapper();
        $currencyMapper = new CurrencyMapper();

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuPayment'), ['controller' => 'payment', 'action' => 'index']);

        $this->getView()->set('settings', $settingsMapper->getSettings());
        $this->getView()->set('currency', $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0]);
    }

    /**
     * Action for Paypal Checkout Advanced
     *
     * @return void
     */
    public function advancedAction()
    {
        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuPaymentAdvanced'), ['controller' => 'payment', 'action' => 'advanced']);

        $this->getView()->set('shopItems', '');
    }
}
