<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Orders extends Model
{
    /**
     * The id of the order.
     *
     * @var int
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
     * The json string of the order.
     *
     * @var string
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
     * A 18 char long selector.
     *
     * @var string
     */
    protected $selector;

    /**
     * A 64 char long confirmCode.
     *
     * @var string
     */
    protected $confirmCode;

    /**
     * The status of the order.
     *
     * @var int
     */
    protected $status;

    /**
     * Gets the id of the order.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the order.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Orders
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the datetime of the order.
     *
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }

    /**
     * Sets the datetime of the order.
     *
     * @param string $datetime
     * @return $this
     */
    public function setDatetime(string $datetime): Orders
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Gets the prename of the order.
     *
     * @return string
     */
    public function getPrename(): string
    {
        return $this->prename;
    }

    /**
     * Sets the prename of the order.
     *
     * @param string $prename
     * @return $this
     */
    public function setPrename(string $prename): Orders
    {
        $this->prename = $prename;

        return $this;
    }

    /**
     * Gets the lastname of the order.
     *
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Sets the lastname of the order.
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): Orders
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets the street of the order.
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Sets the street of the order.
     *
     * @param string $street
     * @return $this
     */
    public function setStreet(string $street): Orders
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Gets the postcode of the order.
     *
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * Sets the postcode of the order.
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode(string $postcode): Orders
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Gets the city of the order.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the city of the order.
     *
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): Orders
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Gets the country of the order.
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets the country of the order.
     *
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): Orders
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Gets the email of the order.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email of the order.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): Orders
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the order/shopping cart of the order as json string.
     *
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * Sets the order/shopping cart of the order as json string.
     *
     * @param string $order
     * @return $this
     */
    public function setOrder(string $order): Orders
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Gets the filename of the invoice.
     *
     * @return string
     */
    public function getInvoiceFilename(): string
    {
        return $this->invoicefilename;
    }

    /**
     * Sets the filename of the invoice.
     *
     * @param string $invoicefilename
     * @return $this
     */
    public function setInvoiceFilename(string $invoicefilename): Orders
    {
        $this->invoicefilename = $invoicefilename;

        return $this;
    }

    /**
     * Gets the datetime when the invoice was sent to the costumer.
     *
     * @return string
     */
    public function getDatetimeInvoiceSent(): string
    {
        return $this->datetimeInvoiceSent;
    }

    /**
     * Sets the datetime when the invoice was sent to the costumer.
     *
     * @param string $datetimeInvoiceSent
     * @return $this
     */
    public function setDatetimeInvoiceSent(string $datetimeInvoiceSent): Orders
    {
        $this->datetimeInvoiceSent = $datetimeInvoiceSent;

        return $this;
    }

    /**
     * Get the 18 char long selector.
     *
     * @return string
     */
    public function getSelector(): ?string
    {
        return $this->selector;
    }

    /**
     * Set the 18 char long selector.
     *
     * @param string $selector
     * @return $this
     */
    public function setSelector(string $selector): Orders
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     * Get the 64 char long confirm code.
     *
     * @return string
     */
    public function getConfirmCode(): ?string
    {
        return $this->confirmCode;
    }

    /**
     * Set the 64 char long confirm code.
     *
     * @param string $confirmCode
     * @return Orders
     */
    public function setConfirmCode(string $confirmCode): Orders
    {
        $this->confirmCode = $confirmCode;
        return $this;
    }

    /**
     * Gets the status of the order.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the status of the order.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Orders
    {
        $this->status = $status;

        return $this;
    }

}
