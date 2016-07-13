<?php
namespace Agere\User\Service;

use Agere\User\Model\User as User;

interface UserAwareInterface {
    /**
     * Set the user object
     *
     * @param User $user
     * @return $this
     */
    public function setUser(User $user);

    /**
     * Get the user object
     *
     * @return User
     */
    public function getUser();
}
