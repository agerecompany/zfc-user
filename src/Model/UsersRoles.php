<?php

namespace Agere\User\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersRoles
 */
class UsersRoles
{
    /**
     * @var integer
     */
   // private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $roleId;

    /**
     * @var \Agere\User\Model\User
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return UsersRoles
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return UsersRoles
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set users

     *
*@param \Agere\User\Model\User $user
     * @return UsersRoles
     */
    public function setUser(\Agere\User\Model\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get users
     *
     * @return \Agere\User\Model\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
