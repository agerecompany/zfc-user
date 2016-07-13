<?php
/**
 * Authentication Factory
 *
 * @category Agere
 * @package Agere_User
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 12.03.2016 1:44
 */
namespace Agere\User\Event\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Agere\User\Controller\Plugin\UserAuthentication;
use Agere\User\Event\Authentication;
use Agere\User\Acl\Acl;

class AuthenticationFactory {

	protected $roles;

	public function __invoke(ServiceLocatorInterface $sm) {
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$userAuthenticationPlugin = $sm->get(UserAuthentication::class);

		$acl = new Acl();
		$auth = new Authentication();
		$auth->setDbAdapter($dbAdapter);
		$auth->setUserAuthenticationPlugin($userAuthenticationPlugin);
		$auth->setAclClass($acl);
		$auth->setRoles($auth->getDbRoles($dbAdapter));

		//$tableRealName = func_get_args()[2];

		return $auth;
	}




}