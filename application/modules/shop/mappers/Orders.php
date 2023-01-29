<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Address;
use Modules\Shop\Models\Order as OrdersModel;
use Modules\Shop\Mappers\Address as AddressMapper;

class Orders extends Mapper
{
    /**
     * Gets orders.
     *
     * @param array $where
     * @return OrdersModel[]|[]
     */
    public function getOrders(array $where = []): array
    {
        $ordersArray = $this->db()->select()
            ->fields(['o.id', 'o.invoiceAddressId', 'o.deliveryAddressId', 'o.datetime', 'o.order', 'o.invoicefilename', 'o.datetimeInvoiceSent', 'o.selector', 'o.confirmCode', 'o.status'])
            ->from(['o' => 'shop_orders'])
            ->join(['c' => 'shop_costumers'], 'o.costumerId = c.id', 'INNER', ['costumerId' => 'c.id', 'c.email'])
            ->join(['ia' => 'shop_addresses'], 'o.invoiceAddressId = ia.id', 'INNER', ['invoiceAddressId' => 'ia.id', 'invoiceAddressCostumerId' => 'ia.costumerId', 'invoiceAddressPrename' => 'ia.prename', 'invoiceAddressLastname' => 'ia.lastname', 'invoiceAddressStreet' => 'ia.street', 'invoiceAddressPostcode' => 'ia.postcode', 'invoiceAddressCity' => 'ia.city', 'invoiceAddressCountry' => 'ia.country'])
            ->join(['da' => 'shop_addresses'], 'o.deliveryAddressId = da.id', 'INNER', ['deliveryAddressId' => 'da.id', 'deliveryAddressCostumerId' => 'da.costumerId', 'deliveryAddressPrename' => 'da.prename', 'deliveryAddressLastname' => 'da.lastname', 'deliveryAddressStreet' => 'da.street', 'deliveryAddressPostcode' => 'da.postcode', 'deliveryAddressCity' => 'da.city', 'deliveryAddressCountry' => 'da.country'])
            ->where($where)
            ->order(['o.status' => 'ASC', 'o.id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($ordersArray)) {
            return [];
        }

        $orders = [];
        foreach ($ordersArray as $orderRow) {
            $orderModel = new OrdersModel();
            $orderModel->setId($orderRow['id']);
            $orderModel->setDatetime($orderRow['datetime']);
            $orderModel->setCostumerId($orderRow['costumerId']);

            $addressModel = new Address();
            $addressModel->setId($orderRow['invoiceAddressId']);
            $addressModel->setCostumerID($orderRow['invoiceAddressCostumerId']);
            $addressModel->setPrename($orderRow['invoiceAddressPrename']);
            $addressModel->setLastname($orderRow['invoiceAddressLastname']);
            $addressModel->setStreet($orderRow['invoiceAddressStreet']);
            $addressModel->setPostcode($orderRow['invoiceAddressPostcode']);
            $addressModel->setCity($orderRow['invoiceAddressCity']);
            $addressModel->setCountry($orderRow['invoiceAddressCountry']);
            $orderModel->setInvoiceAddress($addressModel);

            $addressModel = new Address();
            $addressModel->setId($orderRow['deliveryAddressId']);
            $addressModel->setCostumerID($orderRow['deliveryAddressCostumerId']);
            $addressModel->setPrename($orderRow['deliveryAddressPrename']);
            $addressModel->setLastname($orderRow['deliveryAddressLastname']);
            $addressModel->setStreet($orderRow['deliveryAddressStreet']);
            $addressModel->setPostcode($orderRow['deliveryAddressPostcode']);
            $addressModel->setCity($orderRow['deliveryAddressCity']);
            $addressModel->setCountry($orderRow['deliveryAddressCountry']);
            $orderModel->setDeliveryAddress($addressModel);

            $orderModel->setEmail($orderRow['email']);
            $orderModel->setOrder($orderRow['order']);
            $orderModel->setInvoiceFilename($orderRow['invoicefilename']);
            $orderModel->setDatetimeInvoiceSent($orderRow['datetimeInvoiceSent']);
            $orderModel->setSelector($orderRow['selector'] ?? '');
            $orderModel->setConfirmCode($orderRow['confirmCode'] ?? '');
            $orderModel->setStatus($orderRow['status']);

            $orders[] = $orderModel;
        }

        return $orders;
    }

    /**
     * Gets order by id.
     *
     * @param int $id
     * @return false|OrdersModel
     */
    public function getOrderById(int $id)
    {
        $order = $this->getOrders(['o.id' => $id]);
        return reset($order);
    }

    /**
     * Gets order by selector.
     *
     * @param string $selector
     * @return false|OrdersModel
     */
    public function getOrderBySelector(string $selector)
    {
        $order = $this->getOrders(['o.selector' => $selector]);
        return reset($order);
    }

    /**
     * Get orders by costumer id. Or in other words all orders of a costumer.
     *
     * @param int $costumerId
     * @return OrdersModel[]
     */
    public function getOrdersByCostumerId(int $costumerId): array
    {
        return $this->getOrders(['c.id' => $costumerId]);
    }

    /**
     * Inserts or updates order model.
     *
     * @param OrdersModel $order
     * @return int
     */
    public function save(OrdersModel $order): int
    {
        $addressMapper = new AddressMapper();

        $order->getInvoiceAddress()->setId($addressMapper->save($order->getInvoiceAddress()));
        $order->getDeliveryAddress()->setId($addressMapper->save($order->getDeliveryAddress()));

        $fields = [
            'datetime' => $order->getDatetime(),
            'costumerId' => $order->getCostumerId(),
            'invoiceAddressId' => $order->getInvoiceAddress()->getId(),
            'deliveryAddressId' => $order->getDeliveryAddress()->getId(),
            'order' => $order->getOrder(),
            'invoicefilename' => $order->getInvoiceFilename(),
            'datetimeInvoiceSent' => $order->getDatetimeInvoiceSent(),
            'selector' => $order->getSelector(),
            'confirmCode' => $order->getConfirmCode(),
            'status' => $order->getStatus(),
        ];

        if ($order->getId()) {
            $this->db()->update('shop_orders')
                ->values($fields)
                ->where(['id' => $order->getId()])
                ->execute();
            return $order->getId();
        } else {
            return $this->db()->insert('shop_orders')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Inserts or updates order status.
     *
     * @param OrdersModel $order
     */
    public function updateStatus(OrdersModel $order)
    {
        $this->db()->update('shop_orders')
            ->values(['status' => $order->getStatus()])
            ->where(['id' => $order->getId()])
            ->execute();
    }

    /**
     * Deletes order with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_orders')
            ->where(['id' => $id])
            ->execute();
    }
}
