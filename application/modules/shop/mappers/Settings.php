<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Modules\Shop\Models\Settings as SettingsModel;

class Settings extends \Ilch\Mapper
{
    /**
     * Gets the settings.
     *
     * @return SettingsModel
     */
    public function getSettings()
    {
        $serverRow = $this->db()->select('*')
            ->from('shop_settings')
            ->where(['id' => '1'])
            ->execute()
            ->fetchAssoc();

        if (empty($serverRow)) {
            return null;
        }

        $model = new SettingsModel();
        $model->setId($serverRow['id']);
        $model->setShopName($serverRow['shopName']);
        $model->setShopLogo($serverRow['shopLogo']);
        $model->setShopStreet($serverRow['shopStreet']);
        $model->setShopPlz($serverRow['shopPlz']);
        $model->setShopCity($serverRow['shopCity']);
        $model->setShopTel($serverRow['shopTel']);
        $model->setShopFax($serverRow['shopFax']);
        $model->setShopMail($serverRow['shopMail']);
        $model->setShopWeb($serverRow['shopWeb']);
        $model->setShopStNr($serverRow['shopStNr']);
        $model->setBankName($serverRow['bankName']);
        $model->setBankOwner($serverRow['bankOwner']);
        $model->setBankIBAN($serverRow['bankIBAN']);
        $model->setBankBIC($serverRow['bankBIC']);
        $model->setInvoiceTextTop($serverRow['invoiceTextTop']);
        $model->setInvoiceTextBottom($serverRow['invoiceTextBottom']);
        $model->setAGB($serverRow['agb']);
        $model->setFixTax($serverRow['fixTax']);
        $model->setFixShippingCosts($serverRow['fixShippingCosts']);
        $model->setFixShippingTime($serverRow['fixShippingTime']);

        return $model;
    }
    
    /**
     * Insert or update settingShop.
     *
     * @param SettingsModel $settingShop
     */
    public function updateSettingShop(SettingsModel $settings)
    {
        $this->db()->update('shop_settings')
            ->values([
                      'shopName' => $settings->getShopName(),
                      'shopLogo' => $settings->getShopLogo(),
                      'shopStreet' => $settings->getShopStreet(),
                      'shopPlz' => $settings->getShopPlz(),
                      'shopCity' => $settings->getShopCity(),
                      'shopTel' => $settings->getShopTel(),
                      'shopFax' => $settings->getShopFax(),
                      'shopMail' => $settings->getShopMail(),
                      'shopWeb' => $settings->getShopWeb(),
                      'shopStNr' => $settings->getShopStNr()
                    ])
            ->where(['id' => '1'])
            ->execute();
    }
    
    /**
     * Insert or update settingBank.
     *
     * @param SettingsModel $settingBank
     */
    public function updateSettingBank(SettingsModel $settings)
    {
        $this->db()->update('shop_settings')
            ->values([ 
                      'bankName' => $settings->getBankName(),
                      'bankOwner' => $settings->getBankOwner(),
                      'bankIBAN' => $settings->getBankIBAN(),
                      'bankBIC' => $settings->getBankBIC()
                    ])
            ->where(['id' => '1'])
            ->execute();
    }
    
    /**
     * Insert or update settingDefault.
     *
     * @param SettingsModel $settingDefault
     */
    public function updateSettingDefault(SettingsModel $settings)
    {
        $this->db()->update('shop_settings')
            ->values([
                      'fixTax' => $settings->getFixTax(),
                      'fixShippingCosts' => $settings->getFixShippingCosts(),
                      'fixShippingTime' => $settings->getFixShippingTime(),
                      'invoiceTextTop' => $settings->getInvoiceTextTop(),
                      'invoiceTextBottom' => $settings->getInvoiceTextBottom()
                    ])
            ->where(['id' => '1'])
            ->execute();
    }

    /**
     * Insert or update settingAGB.
     *
     * @param SettingsModel $settingAGB
     */
    public function updateSettingAGB(SettingsModel $settings)
    {
        $this->db()->update('shop_settings')
            ->values([
                      'agb' => $settings->getAGB()
                    ])
            ->where(['id' => '1'])
            ->execute();
    }

}
