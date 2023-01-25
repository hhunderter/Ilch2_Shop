<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

class Costumer extends Model
{
    /**
     * The id of the costumer.
     *
     * @var int
     */
    protected $id;

    /**
     * The user id of the costumer.
     *
     * @var int
     */
    protected $userId;

    /**
     * The email of the costumer.
     *
     * @var string
     */
    protected $email;

    /**
     * Gets the id of the costumer.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the costumer.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Costumer
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the user id of the costumer.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the user id of the costumer.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): Costumer
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets the email of the costumer.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email of the costumer.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): Costumer
    {
        $this->email = $email;

        return $this;
    }
}
