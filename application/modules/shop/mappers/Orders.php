<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Modules\Shop\Models\Orders as OrdersModel;

class Orders extends \Ilch\Mapper
{
    /**
     * Gets orders.
     *
     * @param array $where
     * @return OrdersModel[]|[]
     */
    public function getOrders($where = [])
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
            $orderModel->setStatus($orderRow['status']);

            $orders[] = $orderModel;
        }

        return $orders;
    }

    /**
     * Gets order by id.
     *
     * @param integer $id
     * @return OrdersModel|null
     */
    public function getOrdersById($id)
    {
        $order = $this->getOrders(['id' => $id]);
        return reset($order);
    }

    /**
     * Inserts or updates order model.
     *
     * @param OrdersModel $shop
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
     * @param OrdersModel $shop
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
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('shop_orders')
            ->where(['id' => $id])
            ->execute();
    }
}
