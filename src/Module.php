<?php
namespace Agere\User;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\Event;
use Zend\Http\Request as HttpRequest;
use Agere\User\Acl\Acl;
use Agere\User\Controller\Plugin\UserAuthentication;
use Agere\User\Event\Authentication;

class Module {

	protected $dbAdapter;
	protected $resultRolesArray;
	protected $accessDefault = 6;
	protected $denyDefault = 0;
	protected $roles;
	protected $acl;


	public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
		$sm = $e->getApplication()->getServiceManager();

		// set params in the controller
		$sharedEvents = $eventManager->getSharedManager();

        if ($e->getRequest() instanceof HttpRequest) {
            $auth = $sm->get(Authentication::class);
            $auth->init();
            $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', array(
                $auth,
                'mvcPreDispatch'
            ), 1000); //@todo - Go directly to User\Event\Authentication

        }
	}

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @deprecated
     */
    protected function _accessPage($event, $user)
	{
		// User
		$sm = $event->getTarget()->getServiceLocator();
		/** @var Authentication $auth */
		$auth = $sm->get(Authentication::class);
		/** @var \Agere\User\Acl\Acl $acl */
		$acl = $auth->getAclClass();

		//\Zend\Debug\Debug::dump(get_class($acl)); die(__METHOD__);

		$eventName = $event->getName();

		// Target
		$target = preg_replace('/([a-z]+)+([A-Z])/', '$1-$2', $eventName);
		$target = str_replace(['.', '-action'], ['/', ''], strtolower($target));

		// Access
		$access = Acl::getAccess();
		$accessTotal = Acl::getAccessTotal();

		// Allowed
		$allowed = [$acl->isAllowed($user['mnemo'], 'all', $accessTotal)];

		if ($acl->hasResource($target))
		{
			$allowed[] = $acl->isAllowed($user['mnemo'], $target, $accessTotal);
			$allowed[] = $acl->isAllowed($user['mnemo'], $target, $access['write']);
		}

		$message = (in_array(true, $allowed)) ? '' : 'Доступ запрещен';

		return ['message' => $message];
	}

}