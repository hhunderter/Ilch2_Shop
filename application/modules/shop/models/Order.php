<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;
use Modules\Shop\Models\Address as AddressModel;

class Order extends Model
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
     * The id of the order.
     *
     * @var int
     */
    protected $costumerId;

    /**
     * The invoice address of the order.
     *
     * @var Address
     */
    protected $invoiceAddress;

    /**
     * The delivery address of the order.
     *
     * @var Address
     */
    protected $deliveryAddress;

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

    public function __construct()
    {
        $this->deliveryAddress = new AddressModel();
        $this->invoiceAddress = new AddressModel();
    }

    /**
     * Gets the id of the order.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the order.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Order
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
    public function setDatetime(string $datetime): Order
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get the costumer id.
     *
     * @return int
     */
    public function getCostumerId(): int
    {
        return $this->costumerId;
    }

    /**
     * Set the costumer id.
     *
     * @param int $costumerId
     * @return Order
     */
    public function setCostumerId(int $costumerId): Order
    {
        $this->costumerId = $costumerId;
        return $this;
    }

    /**
     * Get the innvoice address.
     *
     * @return Address
     */
    public function getInvoiceAddress(): Address
    {
        return $this->invoiceAddress;
    }

    /**
     * Set the invoice address
     *
     * @param Address $invoiceAddress
     * @return Order
     */
    public function setInvoiceAddress(Address $invoiceAddress): Order
    {
        $this->invoiceAddress = $invoiceAddress;
        return $this;
    }

    /**
     * Get the delivery address.
     *
     * @return Address
     */
    public function getDeliveryAddress(): Address
    {
        return $this->deliveryAddress;
    }

    /**
     * Set the delivery address.
     *
     * @param Address $deliveryAddress
     * @return Order
     */
    public function setDeliveryAddress(Address $deliveryAddress): Order
    {
        $this->deliveryAddress = $deliveryAddress;
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
    public function setEmail(string $email): Order
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
    public function setOrder(string $order): Order
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Gets the filename of the invoice.
     *
     * @return string
     */
    public function getInvoiceFilename(): ?string
    {
        return $this->invoicefilename;
    }

    /**
     * Sets the filename of the invoice.
     *
     * @param string $invoicefilename
     * @return $this
     */
    public function setInvoiceFilename(string $invoicefilename): Order
    {
        $this->invoicefilename = $invoicefilename;

        return $this;
    }

    /**
     * Gets the datetime when the invoice was sent to the costumer.
     *
     * @return string
     */
    public function getDatetimeInvoiceSent(): ?string
    {
        return $this->datetimeInvoiceSent;
    }

    /**
     * Sets the datetime when the invoice was sent to the costumer.
     *
     * @param string $datetimeInvoiceSent
     * @return $this
     */
    public function setDatetimeInvoiceSent(string $datetimeInvoiceSent): Order
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
    public function setSelector(string $selector): Order
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
     * @return Order
     */
    public function setConfirmCode(string $confirmCode): Order
    {
        $this->confirmCode = $confirmCode;
        return $this;
    }

    /**
     * Gets the status of the order.
     *
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Sets the status of the order.
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): Order
    {
        $this->status = $status;

        return $this;
    }

}
