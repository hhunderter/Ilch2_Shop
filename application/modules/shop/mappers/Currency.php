<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Shop\Models\Currency as CurrencyModel;

class Currency extends Mapper
{
    /**
     * Gets the currencies.
     *
     * @param array $where
     * @return CurrencyModel[]|array
     */
    public function getCurrencies($where = [])
    {
        $currenciesArray = $this->db()->select('*')
            ->from('shop_currencies')
            ->where($where)
            ->order(['name' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($currenciesArray)) {
            return [];
        }

        $currencies = [];

        foreach ($currenciesArray as $currency) {
            $currencyModel = new CurrencyModel();
            $currencyModel->setId($currency['id']);
            $currencyModel->setName($currency['name']);
            $currencies[] = $currencyModel;
        }

        return $currencies;
    }

    /**
     * Gets the currencies by id.
     *
     * @param int $id
     * @return CurrencyModel[]|array
     */
    public function getCurrencyById($id)
    {
        return $this->getCurrencies(['id' => $id]);
    }

    /**
     * Checks if a currency with a specific name exists.
     *
     * @param string $name
     * @return bool
     */
    public function currencyWithNameExists($name)
    {
        return (boolean) $this->db()->select('COUNT(*)', 'shop_currencies', ['name' => $name])
            ->execute()
            ->fetchCell();
    }

    /**
     * Insert or update currencies.
     *
     * @param CurrencyModel $model
     */
    public function save(CurrencyModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('shop_currencies')
                ->values(['name' => $model->getName()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('shop_currencies')
                ->values(['name' => $model->getName()])
                ->execute();
        }
    }

    /**
     * Deletes the currency by id.
     *
     * @param int $id
     * @return Result|int
     */
    public function deleteCurrencyById($id)
    {
        return $this->db()->delete('shop_currencies')
            ->where(['id' => $id])
            ->execute();
    }
}
