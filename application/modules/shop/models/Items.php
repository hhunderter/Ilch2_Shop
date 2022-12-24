<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Items extends Model
{
    /**
     * The id of the item.
     *
     * @var int
     */
    protected $id;

    /**
     * The cat_id of the item.
     *
     * @var int
     */
    protected $catId;

    /**
     * The name of the item.
     *
     * @var string
     */
    protected $name;

     /**
     * The code of the item.
     *
     * @var string
     */
    protected $code;

     /**
     * The itemnumber of the item.
     *
     * @var string
     */
    protected $itemnumber;

     /**
     * The stock of the item.
     *
     * @var int
     */
    protected $stock;

     /**
     * The unitName of the item.
     *
     * @var string
     */
    protected $unitName;

     /**
     * The cordon of the item.
     *
     * @var int
     */
    protected $cordon;

     /**
     * The cordonText of the item.
     *
     * @var string
     */
    protected $cordonText;

     /**
     * The cordonColor of the item.
     *
     * @var string
     */
    protected $cordonColor;

     /**
     * The price of the item.
     *
     * @var string
     */
    protected $price;

     /**
     * The tax of the item.
     *
     * @var int
     */
    protected $tax;

     /**
     * The shippingCosts of the item.
     *
     * @var string
     */
    protected $shippingCosts;

     /**
     * The shippingTime of the item.
     *
     * @var int
     */
    protected $shippingTime;

     /**
     * The image of the item.
     *
     * @var string
     */
    protected $image;

     /**
     * The image1 of the item.
     *
     * @var string
     */
    protected $image1;

     /**
     * The image2 of the item.
     *
     * @var string
     */
    protected $image2;

     /**
     * The image3 of the item.
     *
     * @var string
     */
    protected $image3;

     /**
     * The info of the item.
     *
     * @var string
     */
    protected $info;

     /**
     * The desc of the item.
     *
     * @var string
     */
    protected $desc;

     /**
     * The status of the item.
     *
     * @var string
     */
    protected $status;
    
    /**
     * Gets the id of the item.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the item.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the catId of the item.
     *
     * @return int
     */
    public function getCatId()
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the item.
     *
     * @param int $catId
     * @return $this
     */
    public function setCatId($catId)
    {
        $this->catId = (int)$catId;

        return $this;
    }

    /**
     * Gets the name of the item.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the item.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the itemnumber of the item.
     *
     * @return string
     */
    public function getItemnumber()
    {
        return $this->itemnumber;
    }

    /**
     * Sets the itemnumber of the item.
     *
     * @param string $itemnumber
     * @return $this
     */
    public function setItemnumber($itemnumber)
    {
        $this->itemnumber = (string)$itemnumber;

        return $this;
    }

    /**
     * Gets the code of the item.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets the code of the item.
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = (string)$code;

        return $this;
    }

    /**
     * Gets the stock of the item.
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Sets the stock of the item.
     *
     * @param int $stock
     * @return $this
     */
    public function setStock($stock)
    {
        $this->stock = (int)$stock;

        return $this;
    }

    /**
     * Gets the unitName of the item.
     *
     * @return string
     */
    public function getUnitName()
    {
        return $this->unitName;
    }

    /**
     * Sets the unitName of the item.
     *
     * @param string $unitName
     * @return $this
     */
    public function setUnitName($unitName)
    {
        $this->unitName = (string)$unitName;

        return $this;
    }

    /**
     * Gets the cordon of the item.
     *
     * @return string
     */
    public function getCordon()
    {
        return $this->cordon;
    }

    /**
     * Sets the cordon of the item.
     *
     * @param int $cordon
     * @return $this
     */
    public function setCordon($cordon)
    {
        $this->cordon = (int)$cordon;

        return $this;
    }

    /**
     * Gets the cordonText of the item.
     *
     * @return string
     */
    public function getCordonText()
    {
        return $this->cordonText;
    }

    /**
     * Sets the cordonText of the item.
     *
     * @param string $cordonText
     * @return $this
     */
    public function setCordonText($cordonText)
    {
        $this->cordonText = (string)$cordonText;

        return $this;
    }

    /**
     * Gets the cordonColor of the item.
     *
     * @return string
     */
    public function getCordonColor()
    {
        return $this->cordonColor;
    }

    /**
     * Sets the cordonColor of the item.
     *
     * @param string $cordonColor
     * @return $this
     */
    public function setCordonColor($cordonColor)
    {
        $this->cordonColor = (string)$cordonColor;

        return $this;
    }

    /**
     * Gets the price of the item.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price of the item.
     *
     * @param string $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = (string)$price;

        return $this;
    }

    /**
     * Gets the tax of the item.
     *
     * @return int
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Sets the tax of the item.
     *
     * @param int $tax
     * @return $this
     */
    public function setTax($tax)
    {
        $this->tax = (string)$tax;

        return $this;
    }

    /**
     * Gets the shippingCosts of the item.
     *
     * @return string
     */
    public function getShippingCosts()
    {
        return $this->shippingCosts;
    }

    /**
     * Sets the shippingCosts of the item.
     *
     * @param string $shippingCosts
     * @return $this
     */
    public function setShippingCosts($shippingCosts)
    {
        $this->shippingCosts = (string)$shippingCosts;

        return $this;
    }

    /**
     * Gets the shippingTime of the item.
     *
     * @return int
     */
    public function getShippingTime()
    {
        return $this->shippingTime;
    }

    /**
     * Sets the shippingTime of the item.
     *
     * @param string $shippingTime
     * @return $this
     */
    public function setShippingTime($shippingTime)
    {
        $this->shippingTime = (int)$shippingTime;

        return $this;
    }

    /**
     * Gets the preview image of the item.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Sets the preview image of the item.
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = (string)$image;

        return $this;
    }

    /**
     * Gets the image1 of the item.
     *
     * @return string
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * Sets the image1 of the item.
     *
     * @param string $image1
     * @return $this
     */
    public function setImage1($image1)
    {
        $this->image1 = (string)$image1;

        return $this;
    }

    /**
     * Gets the image2 of the item.
     *
     * @return string
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * Sets the image2 of the item.
     *
     * @param string $image2
     * @return $this
     */
    public function setImage2($image2)
    {
        $this->image2 = (string)$image2;

        return $this;
    }

    /**
     * Gets the image3 of the item.
     *
     * @return string
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Sets the image3 of the item.
     *
     * @param string $image3
     * @return $this
     */
    public function setImage3($image3)
    {
        $this->image3 = (string)$image3;

        return $this;
    }

    /**
     * Gets the short info of the item.
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets the short info of the item.
     *
     * @param string $info
     * @return $this
     */  
    public function setInfo($info)
    {
        $this->info = (string)$info;

        return $this;
    }  

    /**
     * Gets the description of the item.
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets the description of the item.
     *
     * @param string $desc
     * @return $this
     */ 
    public function setDesc($desc)
    {
        $this->desc = (string)$desc;

        return $this;
    }    

    /**
     * Gets the status of the item.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the description of the item.
     *
     * @param int $status
     * @return $this
     */ 
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    } 

}
