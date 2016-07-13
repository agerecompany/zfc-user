<?php
namespace Agere\User\Controller\Plugin\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

use Agere\User\Controller\Plugin\UserPlugin;

class UserFactory implements FactoryInterface {

	public function createService(ServiceLocatorInterface $cpm) {
		/** @var HelperPluginManager $sm */
		//$locator = $sm->getServiceLocator();
		//return new User($locator->get('ControllerPluginManager')->get('user'));

		$sm = $cpm->getServiceLocator();

		$authService = $sm->get('UserAuthentication')->getAuthService();
		$userService = $sm->get('UserService');
		//$accessService = $sm->get('AccessService');
        /** @var \Agere\User\Event\Authentication $authEvent */
        $authEvent = $sm->get('Agere\User\Event\Authentication');
        $acl = $authEvent->getAclClass();
        $simpler = $cpm->get('simpler');

        //\Zend\Debug\Debug::dump(get_class($acl)); die(__METHOD__);

		$userPlugin = new UserPlugin($authService, $userService, $acl);
		$userPlugin->setSimpler($simpler);
		
		return $userPlugin;
	}

}