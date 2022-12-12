<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

class Orders extends \Ilch\Model
{
    /**
     * The id of the order.
     *
     * @var integer
     */
    protected $id;

    /**
     * The datetime of the order.
     *
     * @var string
     */
    protected $datetime;

    /**
     * The prename of the order.
     *
     * @var string
     */
    protected $prename;

    /**
     * The lastname of the order.
     *
     * @var string
     */
    protected $lastname;

    /**
     * The street of the order.
     *
     * @var string
     */
    protected $street;

    /**
     * The postcode of the order.
     *
     * @var string
     */
    protected $postcode;

    /**
     * The city of the order.
     *
     * @var string
     */
    protected $city;

    /**
     * The country of the order.
     *
     * @var string
     */
    protected $country;

    /**
     * The email of the order.
     *
     * @var string
     */
    protected $email;

    /**
     * The orderarray of the order.
     *
     * @var array
     */
    protected $order;

    /**
     * The filename of the invoice.
     *
     * @var string
     */
    protected $invoicefilename;

    /**
     * The datetime when the invoice was sent to the costumer.
     *
     * @var string
     */
    protected $datetimeInvoiceSent;

    /**
     * The status of the order.
     *
     * @var integer
     */
    protected $status;

    /**
     * Gets the id of the order.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the order.
     *
     * @param integer $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the datetime of the order.
     *
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Sets the datetime of the order.
     *
     * @param string $datetime
     * @return this
     */
    public function setDatetime($datetime)
    {
        $this->datetime = (string)$datetime;

        return $this;
    }

    /**
     * Gets the prename of the order.
     *
     * @return string
     */
    public function getPrename()
    {
        return $this->prename;
    }

    /**
     * Sets the prename of the order.
     *
     * @param string $prename
     * @return this
     */
    public function setPrename($prename)
    {
        $this->prename = (string)$prename;

        return $this;
    }

    /**
     * Gets the lastname of the order.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the lastname of the order.
     *
     * @param string $lastname
     * @return this
     */
    public function setLastname($lastname)
    {
        $this->lastname = (string)$lastname;

        return $this;
    }

    /**
     * Gets the street of the order.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Sets the street of the order.
     *
     * @param string $street
     * @return this
     */
    public function setStreet($street)
    {
        $this->street = (string)$street;

        return $this;
    }

    /**
     * Gets the postcode of the order.
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Sets the postcode of the order.
     *
     * @param string $street
     * @return this
     */
    public function setPostcode($postcode)
    {
        $this->postcode = (string)$postcode;

        return $this;
    }

    /**
     * Gets the city of the order.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city of the order.
     *
     * @param string $city
     * @return this
     */
    public function setCity($city)
    {
        $this->city = (string)$city;

        return $this;
    }

    /**
     * Gets the country of the order.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country of the order.
     *
     * @param string $country
     * @return this
     */
    public function setCountry($country)
    {
        $this->country = (string)$country;

        return $this;
    }

    /**
     * Gets the email of the order.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email of the order.
     *
     * @param string $email
     * @return this
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;

        return $this;
    }

    /**
     * Gets the orderarray of the order.
     *
     * @return array
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the orderarray of the order.
     *
     * @param array $order
     * @return this
     */
    public function setOrder($order)
    {
        $this->order = (string)$order;

        return $this;
    }

    /**
     * Gets the filename of the invoice.
     *
     * @return string
     */
    public function getInvoiceFilename()
    {
        return $this->invoicefilename;
    }

    /**
     * Sets the filename of the invoice.
     *
     * @param string $invoicefilename
     * @return this
     */
    public function setInvoiceFilename($invoicefilename)
    {
        $this->invoicefilename = (string)$invoicefilename;

        return $this;
    }

    /**
     * Gets the datetime when the invoice was sent to the costumer.
     *
     * @return string
     */
    public function getDatetimeInvoiceSent()
    {
        return $this->datetimeInvoiceSent;
    }

    /**
     * Sets the datetime when the invoice was sent to the costumer.
     *
     * @param string $datetimeInvoiceSent
     * @return this
     */
    public function setDatetimeInvoiceSent($datetimeInvoiceSent)
    {
        $this->datetimeInvoiceSent = (string)$datetimeInvoiceSent;

        return $this;
    }

    /**
     * Gets the status of the order.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status of the order.
     *
     * @param integer $status
     * @return this
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    }

}
