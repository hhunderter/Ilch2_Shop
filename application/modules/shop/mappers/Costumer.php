<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Costumer as CostumerModel;

class Costumer extends Mapper
{
    /**
     * Gets costumers.
     *
     * @param array $where
     * @return CostumerModel[]|[]
     */
    public function getCostumers(array $where = []): array
    {
        $costumersArray = $this->db()->select('*')
            ->from('shop_costumers')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($costumersArray)) {
            return [];
        }

        $costumers = [];
        foreach ($costumersArray as $costumerRow) {
            $costumerModel = new CostumerModel();
            $costumerModel->setId($costumerRow['id']);
            $costumerModel->setUserId($costumerRow['userId']);
            $costumerModel->setEmail($costumerRow['email']);

            $costumers[] = $costumerModel;
        }

        return $costumers;
    }

    /**
     * Gets costumer by id.
     *
     * @param int $id
     * @return false|CostumerModel
     */
    public function getCostumerById(int $id)
    {
        $costumer = $this->getCostumers(['id' => $id]);
        return reset($costumer);
    }

    /**
     * Get costumer by user id.
     *
     * @param int $userId
     * @return false|CostumerModel
     */
    public function getCostumerByUserId(int $userId)
    {
        $costumer = $this->getCostumers(['userId' => $userId]);
        return reset($costumer);
    }

    /**
     * Inserts or updates costumer model.
     *
     * @param CostumerModel $costumer
     */
    public function save(CostumerModel $costumer)
    {
        $fields = [
            'userId' => $costumer->getUserId(),
            'email' => $costumer->getEmail(),
        ];

        if ($costumer->getId()) {
            $this->db()->update('shop_costumers')
                ->values($fields)
                ->where(['id' => $costumer->getId()])
                ->execute();
        } else {
            $this->db()->insert('shop_costumers')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes costumer with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_costumers')
            ->where(['id' => $id])
            ->execute();
    }
}
