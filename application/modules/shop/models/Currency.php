<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Currency extends Model
{
    /**
     * The id of the currency.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the currency.
     *
     * @var string
     */
    protected $name;

    /**
     * Gets the id of the currency.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the name of the currency.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the id of the currency.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the name of the currency.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string)$name;
    }
}
