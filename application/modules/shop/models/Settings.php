<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Settings extends Model
{
    /**
     * The id of the settings.
     *
     * @var int
     */
    protected $id;
    
    /**
     * The shopName of the settings.
     *
     * @var string
     */
    protected $shopName;

    /**
     * The shopLogo of the settings.
     *
     * @var string
     */
    protected $shopLogo;

    /**
     * The shopStreet of the settings.
     *
     * @var string
     */
    protected $shopStreet;

    /**
     * The shopPlz of the settings.
     *
     * @var string
     */
    protected $shopPlz;

    /**
     * The shopCity of the settings.
     *
     * @var string
     */
    protected $shopCity;

    /**
     * The shopTel of the settings.
     *
     * @var string
     */
    protected $shopTel;

    /**
     * The shopFax of the settings.
     *
     * @var string
     */
    protected $shopFax;

    /**
     * The shopMail of the settings.
     *
     * @var string
     */
    protected $shopMail;

    /**
     * The shopWeb of the settings.
     *
     * @var string
     */
    protected $shopWeb;

    /**
     * The shopStNr of the settings.
     *
     * @var string
     */
    protected $shopStNr;

    /**
     * The bankName of the settings.
     *
     * @var string
     */
    protected $bankName;

    /**
     * The bankOwner of the settings.
     *
     * @var string
     */
    protected $bankOwner;

    /**
     * The bankIBAN of the settings.
     *
     * @var string
     */
    protected $bankIBAN;

    /**
     * The bankBIC of the settings.
     *
     * @var string
     */
    protected $bankBIC;
    
    /**
     * The invoiceTextTop of the settings.
     *
     * @var string
     */
    protected $invoiceTextTop;
    
    /**
     * The invoiceTextBottom of the settings.
     *
     * @var string
     */
    protected $invoiceTextBottom;
    
    /**
     * The agb of the settings.
     *
     * @var string
     */
    protected $agb;

    /**
     * The fixTax of the settings.
     *
     * @var int
     */
    protected $fixTax;

    /**
     * The fixShippingCosts of the settings.
     *
     * @var string
     */
    protected $fixShippingCosts;

    /**
     * The fixShippingTime of the settings.
     *
     * @var int
     */
    protected $fixShippingTime;

    /**
     * The paypal client id of the settings.
     *
     * @var string
     */
    protected $clientID;

    /**
     * Gets the id of the settings.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the settings.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the shopName of the settings.
     *
     * @return string
     */
    public function getShopName()
    {
        return $this->shopName;
    }

    /**
     * Sets the shopName of the settings.
     *
     * @param string $shopName
     */
    public function setShopName($shopName)
    {
        $this->shopName = (string)$shopName;
    }
    
    /**
     * Gets the shopLogo of the settings.
     *
     * @return string
     */
    public function getShopLogo()
    {
        return $this->shopLogo;
    }

    /**
     * Sets the shopLogo of the settings.
     *
     * @param string $shopLogo
     */
    public function setShopLogo($shopLogo)
    {
        $this->shopLogo = (string)$shopLogo;
    }

    /**
     * Gets the shopStreet of the settings.
     *
     * @return string
     */
    public function getShopStreet()
    {
        return $this->shopStreet;
    }

    /**
     * Sets the shopStreet of the settings.
     *
     * @param string $shopStreet
     */
    public function setShopStreet($shopStreet)
    {
        $this->shopStreet = (string)$shopStreet;
    }

    /**
     * Gets the shopPlz of the settings.
     *
     * @return string
     */
    public function getShopPlz()
    {
        return $this->shopPlz;
    }

    /**
     * Sets the shopPlz of the settings.
     *
     * @param string $shopPlz
     */
    public function setShopPlz($shopPlz)
    {
        $this->shopPlz = (string)$shopPlz;
    }

    /**
     * Gets the shopCity of the settings.
     *
     * @return string
     */
    public function getShopCity()
    {
        return $this->shopCity;
    }

    /**
     * Sets the shopCity of the settings.
     *
     * @param string $shopCity
     */
    public function setShopCity($shopCity)
    {
        $this->shopCity = (string)$shopCity;
    }

    /**
     * Gets the shopTel of the settings.
     *
     * @return string
     */
    public function getShopTel()
    {
        return $this->shopTel;
    }

    /**
     * Sets the shopTel of the settings.
     *
     * @param string $shopTel
     */
    public function setShopTel($shopTel)
    {
        $this->shopTel = (string)$shopTel;
    }

    /**
     * Gets the shopFax of the settings.
     *
     * @return string
     */
    public function getShopFax()
    {
        return $this->shopFax;
    }

    /**
     * Sets the shopFax of the settings.
     *
     * @param string $shopFax
     */
    public function setShopFax($shopFax)
    {
        $this->shopFax = (string)$shopFax;
    }

    /**
     * Gets the shopMail of the settings.
     *
     * @return string
     */
    public function getShopMail()
    {
        return $this->shopMail;
    }

    /**
     * Sets the shopMail of the settings.
     *
     * @param string $shopMail
     */
    public function setShopMail($shopMail)
    {
        $this->shopMail = (string)$shopMail;
    }

    /**
     * Gets the shopWeb of the settings.
     *
     * @return string
     */
    public function getShopWeb()
    {
        return $this->shopWeb;
    }

    /**
     * Sets the shopWeb of the settings.
     *
     * @param string $shopWeb
     */
    public function setShopWeb($shopWeb)
    {
        $this->shopWeb = (string)$shopWeb;
    }

    /**
     * Gets the shopStNr of the settings.
     *
     * @return string
     */
    public function getShopStNr()
    {
        return $this->shopStNr;
    }

    /**
     * Sets the shopStNr of the settings.
     *
     * @param string $shopStNr
     */
    public function setShopStNr($shopStNr)
    {
        $this->shopStNr = (string)$shopStNr;
    }
    
    /**
     * Gets the bankName of the settings.
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets the bankName of the settings.
     *
     * @param string $bankName
     */
    public function setBankName($bankName)
    {
        $this->bankName = (string)$bankName;
    }

    /**
     * Gets the bankOwner of the settings.
     *
     * @return string
     */
    public function getBankOwner()
    {
        return $this->bankOwner;
    }

    /**
     * Sets the bankOwner of the settings.
     *
     * @param string $bankOwner
     */
    public function setBankOwner($bankOwner)
    {
        $this->bankOwner = (string)$bankOwner;
    }

    /**
     * Gets the bankIBAN of the settings.
     *
     * @return string
     */
    public function getBankIBAN()
    {
        return $this->bankIBAN;
    }

    /**
     * Sets the bankIBAN of the settings.
     *
     * @param string $bankIBAN
     */
    public function setBankIBAN($bankIBAN)
    {
        $this->bankIBAN = (string)$bankIBAN;
    }

    /**
     * Gets the bankBIC of the settings.
     *
     * @return string
     */
    public function getBankBIC()
    {
        return $this->bankBIC;
    }

    /**
     * Sets the bankBIC of the settings.
     *
     * @param string $bankBIC
     */
    public function setBankBIC($bankBIC)
    {
        $this->bankBIC = (string)$bankBIC;
    }

    /**
     * Gets the invoiceTextTop of the settings.
     *
     * @return string
     */
    public function getInvoiceTextTop()
    {
        return $this->invoiceTextTop;
    }

    /**
     * Sets the invoiceTextTop of the settings.
     *
     * @param string $invoiceTextTop
     */
    public function setInvoiceTextTop($invoiceTextTop)
    {
        $this->invoiceTextTop = (string)$invoiceTextTop;
    }

    /**
     * Gets the invoiceTextBottom of the settings.
     *
     * @return string
     */
    public function getInvoiceTextBottom()
    {
        return $this->invoiceTextBottom;
    }

    /**
     * Sets the invoiceTextBottom of the settings.
     *
     * @param string $invoiceTextBottom
     */
    public function setInvoiceTextBottom($invoiceTextBottom)
    {
        $this->invoiceTextBottom = (string)$invoiceTextBottom;
    }

    /**
     * Gets the agb of the settings.
     *
     * @return string
     */
    public function getAGB()
    {
        return $this->agb;
    }
    
    /**
     * Sets the agb of the settings.
     *
     * @param string $agb
     */
    public function setAGB($agb)
    {
        $this->agb = (string)$agb;
    }

    /**
     * Gets the fixTax of the settings.
     *
     * @return int
     */
    public function getFixTax()
    {
        return $this->fixTax;
    }
    
    /**
     * Sets the fixTax of the settings.
     *
     * @param int $fixTax
     */
    public function setFixTax($fixTax)
    {
        $this->fixTax = (int)$fixTax;
    }

    /**
     * Gets the fixShippingCosts of the settings.
     *
     * @return string
     */
    public function getFixShippingCosts()
    {
        return $this->fixShippingCosts;
    }
    
    /**
     * Sets the fixShippingCosts of the settings.
     *
     * @param string $fixShippingCosts
     */
    public function setFixShippingCosts($fixShippingCosts)
    {
        $this->fixShippingCosts = (string)$fixShippingCosts;
    }

    /**
     * Gets the fixShippingTime of the settings.
     *
     * @return int
     */
    public function getFixShippingTime()
    {
        return $this->fixShippingTime;
    }
    
    /**
     * Sets the fixShippingTime of the settings.
     *
     * @param int $fixShippingTime
     */
    public function setFixShippingTime($fixShippingTime)
    {
        $this->fixShippingTime = (int)$fixShippingTime;
    }

    /**
     * @return string
     */
    public function getClientID(): string
    {
        return $this->clientID;
    }

    /**
     * @param string $clientID
     */
    public function setClientID(string $clientID): void
    {
        $this->clientID = $clientID;
    }
}
