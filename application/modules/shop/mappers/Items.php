<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Modules\Shop\Models\Items as ItemsModel;

class Items extends \Ilch\Mapper
{
    /**
     * Gets items.
     *
     * @param array $where
     * @return ItemModel[]|[]
     */
    public function getShopItems($where = [])
    {
        $itemsArray = $this->db()->select('*')
            ->from('shop_items')
            ->where($where)
            ->order(['name' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($itemsArray)) {
            return [];
        }

        $items = [];
        foreach ($itemsArray as $itemRow) {
            $itemModel = new ItemsModel();
            $itemModel->setId($itemRow['id']);
            $itemModel->setCode($itemRow['code']);
            $itemModel->setCatId($itemRow['cat_id']);
            $itemModel->setName($itemRow['name']);
            $itemModel->setItemnumber($itemRow['itemnumber']);
            $itemModel->setStock($itemRow['stock']);
            $itemModel->setUnitName($itemRow['unitName']);
            $itemModel->setCordon($itemRow['cordon']);
            $itemModel->setCordonText($itemRow['cordonText']);
            $itemModel->setCordonColor($itemRow['cordonColor']);
            $itemModel->setPrice($itemRow['price']);
            $itemModel->setTax($itemRow['tax']);
            $itemModel->setShippingCosts($itemRow['shippingCosts']);
            $itemModel->setShippingTime($itemRow['shippingTime']);
            $itemModel->setImage($itemRow['image']);
            $itemModel->setImage1($itemRow['image1']);
            $itemModel->setImage2($itemRow['image2']);
            $itemModel->setImage3($itemRow['image3']);
            $itemModel->setInfo($itemRow['info']);
            $itemModel->setDesc($itemRow['desc']);
            $itemModel->setStatus($itemRow['status']);
            
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets items by id.
     *
     * @param integer $id
     * @return ItemsModel|null
     */
    public function getShopById($id)
    {
        $shopItem = $this->getShopItems(['id' => $id]);
        return reset($shopItem);
    }

    /**
     * Gets items by catId.
     *
     * @param integer $catId
     * @return ItemsModel[]|[]
     */
    public function getShopItemsByCatId($catId)
    {
        $shops = $this->getShopItems(['cat_id' => $catId]);
        return reset($shops);
    }

    /**
     * Inserts or updates item model.
     *
     * @param ItemsModel $shop
     */
    public function save(ItemsModel $item)
    {
        $fields = [
            'code' => strtolower(preg_replace('/[^a-z0-9]/i', '', $item->getName())).'_'.time(),
            'cat_id' => $item->getCatId(),
            'name' => $item->getName(),
            'itemnumber' => $item->getItemnumber(),
            'stock' => $item->getStock(),
            'unitName' => $item->getUnitName(),
            'cordon' => $item->getCordon(),
            'cordonText' => $item->getCordonText(),
            'cordonColor' => $item->getCordonColor(),
            'price' => $item->getPrice(),
            'tax' => $item->getTax(),
            'shippingCosts' => $item->getShippingCosts(),
            'shippingTime' => $item->getShippingTime(),
            'image' => $item->getImage(),
            'image1' => $item->getImage1(),
            'image2' => $item->getImage2(),
            'image3' => $item->getImage3(),
            'info' => $item->getInfo(),
            'desc' => $item->getDesc(),
            'status' => $item->getStatus(),          
        ];

        if ($item->getId()) {
            $this->db()->update('shop_items')
                ->values($fields)
                ->where(['id' => $item->getId()])
                ->execute();
        } else {
            $this->db()->insert('shop_items')
                ->values($fields)
                ->execute();
        }
    }
    
    /**
     * Update item stock model.
     *
     * @param ItemsModel $shop
     */
    public function updateStock($id, $orderStock)
    {
        $dbStock = $this->db()->select('stock')
            ->from('shop_items')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        $newStock = $dbStock - $orderStock;
        
        $this->db()->update('shop_items')
            ->values(['stock' => $newStock])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes item with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('shop_items')
            ->where(['id' => $id])
            ->execute();
    }
}
