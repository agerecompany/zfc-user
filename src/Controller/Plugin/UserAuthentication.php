<?php
/**
 * File for UserAuthentication Class
 *
 * @category   User
 * @package    User_Controller
 * @subpackage User_Controller_Plugin
 * @author     Marco Neumann <webcoder_at_binware_dot_org>
 * @copyright  Copyright (c) 2011, Marco Neumann
 * @license    http://binware.org/license/home/type:new-bsd New BSD License
 */

namespace Agere\User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Authentication\AuthenticationService,
    Agere\User\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter,
	Zend\Authentication\Adapter\Exception\RuntimeException;

/**
 * Class for User Authentication
 *
 * Handles Auth Adapter and Auth Service to check Identity
 *
 * @category   User
 * @package    User_Controller
 * @subpackage User_Controller_Plugin
 * @copyright  Copyright (c) 2011, Marco Neumann
 * @license    http://binware.org/license/home/type:new-bsd New BSD License
 */
class UserAuthentication extends AbstractPlugin
{
    /**
     * @var AuthAdapter
     */
    protected $_authAdapter = null;

    /**
     * @var AuthenticationService
     */
    protected $_authService = null;


    /**
     * Check if Identity is present
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * Return current Identity
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * Sets Auth Adapter
     *
     * @param AuthAdapter $authAdapter
     * @return UserAuthentication
     */
    public function setAuthAdapter(AuthAdapter $authAdapter) {
        $this->_authAdapter = $authAdapter;

        return $this;
    }

	/**
	 * Returns Auth Adapter
	 *
	 * @return null|AuthAdapter
	 * @throws \Zend\Authentication\Adapter\Exception\RuntimeException
	 */
	public function getAuthAdapter() {
        if ($this->_authAdapter === null) {
            //$this->setAuthAdapter(new AuthAdapter());
			throw new RuntimeException(__CLASS__ . '::_authAdapter should be set via setAuthAdapter() method!');
        }

        return $this->_authAdapter;
    }

    /**
     * Sets Auth Service
     *
     * @param \Zend\Authentication\AuthenticationService $authService
     * @return UserAuthentication
     */
    public function setAuthService(AuthenticationService $authService) {
        $this->_authService = $authService;

        return $this;
    }

    /**
     * Gets Auth Service
     *
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService()
    {
        if ($this->_authService === null) {
            $this->setAuthService(new AuthenticationService());
        }

        return $this->_authService;
    }

}
