<?php
namespace Agere\User\Service;

use Agere\User\Model\User as User;

/**
 * Trait to provide user aware setter and getter
 */
trait UserAwareTrait {
	/**
	 * @var User
	 */
	protected $user;

	/**
	 * Set the user object
	 *
	 * @param User $user
	 * @return $this
	 */
	public function setUser(User $user) {
		$this->user = $user;

		return $this;
	}

	/**
	 * Get the user object
	 *
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

}
