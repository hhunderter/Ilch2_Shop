<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Orders as OrdersModel;

class Orders extends Mapper
{
    /**
     * Gets orders.
     *
     * @param array $where
     * @return OrdersModel[]|[]
     */
    public function getOrders(array $where = [])
    {
        $ordersArray = $this->db()->select('*')
            ->from('shop_orders')
            ->where($where)
            ->order(['status' => 'ASC', 'id' => 'DESC'])
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
            $orderModel->setPrename($orderRow['prename']);
            $orderModel->setLastname($orderRow['lastname']);
            $orderModel->setStreet($orderRow['street']);
            $orderModel->setPostcode($orderRow['postcode']);
            $orderModel->setCity($orderRow['city']);
            $orderModel->setCountry($orderRow['country']);
            $orderModel->setEmail($orderRow['email']);
            $orderModel->setOrder($orderRow['order']);
            $orderModel->setInvoiceFilename($orderRow['invoicefilename']);
            $orderModel->setDatetimeInvoiceSent($orderRow['datetimeInvoiceSent']);
            $orderModel->setSelector($orderRow['selector']);
            $orderModel->setConfirmCode($orderRow['confirmCode']);
            $orderModel->setStatus($orderRow['status']);

            $orders[] = $orderModel;
        }

        return $orders;
    }

    /**
     * Gets order by id.
     *
     * @param int $id
     * @return OrdersModel|null
     */
    public function getOrdersById(int $id)
    {
        $order = $this->getOrders(['id' => $id]);
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
        $order = $this->getOrders(['selector' => $selector]);
        return reset($order);
    }

    /**
     * Inserts or updates order model.
     *
     * @param OrdersModel $order
     */
    public function save(OrdersModel $order)
    {
        $fields = [
            'datetime' => $order->getDatetime(),
            'prename' => $order->getPrename(),
            'lastname' => $order->getLastname(),
            'street' => $order->getStreet(),
            'postcode' => $order->getPostcode(),
            'city' => $order->getCity(),
            'country' => $order->getCountry(),
            'email' => $order->getEmail(),
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
        } else {
            $this->db()->insert('shop_orders')
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
