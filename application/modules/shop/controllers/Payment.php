<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;

class Payment extends Frontend
{
    public function indexAction()
    {
        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuPayment'), ['controller' => 'payment', 'action' => 'index']);

        $this->getView()->set('shopItems', '');
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
