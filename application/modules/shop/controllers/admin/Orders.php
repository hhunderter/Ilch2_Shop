<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Models\Orders as OrdersModel;
use Ilch\Accesses;

class Orders extends \Ilch\Controller\Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'orders', 'action' => 'index']),
                [
                    'name' => 'manage',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'orders', 'action' => 'treat'])
                ]
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

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuOrders',
            $items
        );
    }

    public function indexAction()
    {
        $ordersMapper = new OrdersMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuOrders'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_orders')) {
            $orderInUse = 0;
            foreach ($this->getRequest()->getPost('check_orders') as $orderId) {
                if ($ordersMapper->getOrdersById($orderId)->getStatus() == 0 || $ordersMapper->getOrdersById($orderId)->getStatus() == 1) {
                    $orderInUse++;
                    continue;
                }
                $ordersMapper->delete($orderId);
            }
            if ($orderInUse > 0) {
                $this->addMessage('ordersInUse', 'danger');
            } else {
                $this->addMessage('deleteSuccess');
            }
        }
        
        $this->getView()->set('ordersMapper', $ordersMapper->getOrders());
    }

    public function treatAction()
    {
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $ordersMapper = new OrdersMapper();
        $settingsMapper = new SettingsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuOrders'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'treat', 'id' => 'treat']);

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'))[0];

        if ($this->getRequest()->getParam('id')) {
            $this->getView()->set('order', $ordersMapper->getOrdersById($this->getRequest()->getParam('id')));
            $this->getView()->set('currency', $currency->getName());
            $this->getView()->set('itemsMapper', $itemsMapper);
            $this->getView()->set('ordersMapper', $ordersMapper);
            $this->getView()->set('settingsMapper', $settingsMapper);
        }

        if ($this->getRequest()->isPost()) {
            $model = new OrdersModel();

            if ($this->getRequest()->getPost('status') != '') {
                $model->setId($this->getRequest()->getPost('id'));
                $model->setStatus($this->getRequest()->getPost('status'));
                $ordersMapper->updateStatus($model);
                $this->redirect(['action' => 'treat', 'id' => $this->getRequest()->getPost('id')]);
            }

            if ($this->getRequest()->getPost('delete') == 1) {
                if ($ordersMapper->getOrdersById($this->getRequest()->getParam('id'))->getStatus() == 0 || $ordersMapper->getOrdersById($this->getRequest()->getParam('id'))->getStatus() == 1) {
                    $this->addMessage('orderInUse', 'danger');
                } else {
                    $ordersMapper->delete($this->getRequest()->getParam('id'));
                    $this->addMessage('deleteSuccess');
                    $this->redirect(['action' => 'index']);
                }
            }
        }
    }

    public function downloadAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        set_time_limit(0);
        $shopInvoicePath = ROOT_PATH.'/application/modules/shop/static/invoice/';

        $id = $this->getRequest()->getParam('id');

        if (!empty($id)) {
            $ordersMapper = new OrdersMapper();
            $order = $ordersMapper->getOrdersById($id);

            if ($order !== null) {
                $fullPath = $shopInvoicePath.$order->getInvoiceFilename().'.pdf';
                if ($fd = fopen($fullPath, 'rb')) {
                    $path_parts = pathinfo($fullPath);
                    // Remove the random part of the filename as it should not end in e.g. the browser history.
                    $publicFileName = preg_replace('/_[^_.]*\./', '.', $path_parts['basename']);

                    header('Content-type: application/pdf');
                    header('Content-Disposition: filename="' .$publicFileName. '"');
                    header('Content-length: ' .filesize($fullPath));
                    // RFC2616 section 14.9.1: Indicates that all or part of the response message is intended for a single user and MUST NOT be cached by a shared cache, such as a proxy server.
                    header('Cache-control: private');
                    while(!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                } else {
                    $this->addMessage('invoiceNotFound', 'danger');
                }
                fclose($fd);
            }
        }

        $this->redirect(['controller' => 'orders', 'action' => 'treat', 'id' => $id]);
    }

    public function sendInvoiceAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        $emailsMapper = new EmailsMapper();
        $orderMapper = new OrdersMapper();
        $settingsMapper = new SettingsMapper();

        $id = $this->getRequest()->getParam('id');
        $order = $orderMapper->getOrdersById($id);
        $shopInvoicePath = '/application/modules/shop/static/invoice/';
        $pathInvoice = ROOT_PATH.$shopInvoicePath.$order->getInvoiceFilename().'.pdf';
        $path_parts = pathinfo($pathInvoice);
        $publicFileNameInvoice = preg_replace('/_[^_.]*\./', '.', $path_parts['basename']);

        // Send invoice to customer.
        $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
        $date = new \Ilch\Date();
        $mailContent = $emailsMapper->getEmail('shop', 'send_invoice_mail', $this->getTranslator()->getLocale());
        $name = $this->getLayout()->escape($order->getLastname());

        $layout = $_SESSION['layout'] ?? '';

        if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/shop/layouts/mail/sendinvoice.php')) {
            $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/shop/layouts/mail/sendinvoice.php');
        } else {
            $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/shop/layouts/mail/sendinvoice.php');
        }
        $messageReplace = [
            '{content}' => $this->getLayout()->purify($mailContent->getText()),
            '{shopname}' => $this->getLayout()->escape($settingsMapper->getSettings()->getShopName()),
            '{date}' => $date->format('l, d. F Y', true),
            '{name}' => $name,
            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
        ];
        $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

        $mail = new \Ilch\Mail();
        $mail->setFromName($siteTitle)
            ->setFromEmail($this->getConfig()->get('standardMail'))
            ->setToName($name)
            ->setToEmail($order->getEmail())
            ->setSubject($this->getLayout()->purify($mailContent->getDesc()))
            ->setMessage($message)
            ->addAttachment($pathInvoice, $publicFileNameInvoice)
            ->send();

        $order->setDatetimeInvoiceSent(new \Ilch\Date('now', $this->getConfig()->get('timezone')));
        $orderMapper->save($order);

        $this->addMessage('sendInvoiceSuccess');
        $this->redirect(['controller' => 'orders', 'action' => 'treat', 'id' => $id]);
    }

    public function delOrderAction()
    {
        if ($this->getRequest()->isSecure()) {
            $ordersMapper = new OrdersMapper();
            if ($ordersMapper->getOrdersById($this->getRequest()->getParam('id'))->getStatus() == 0 || $ordersMapper->getOrdersById($this->getRequest()->getParam('id'))->getStatus() == 1) {
                $this->addMessage('orderInUse', 'danger');
            } else {
                $ordersMapper->delete($this->getRequest()->getParam('id'));
                $this->addMessage('deleteSuccess');
            }
        }
        $this->redirect(['action' => 'index']);
    }
}
