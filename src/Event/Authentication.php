<?php
/**
 * File for Event Class
 *
 * @category  User
 * @package   User_Event
 * @author    Marco Neumann <webcoder_at_binware_dot_org>
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/home/type:new-bsd New BSD License
 */

/**
 * @namespace
 */
namespace Agere\User\Event;

/**
 * @uses Zend\Mvc\MvcEvent
 * @uses User\Controller\Plugin\UserAuthentication
 * @uses User\Acl\Acl
 */
use Zend\Mvc\MvcEvent as MvcEvent;
	//Zend\Permissions\Acl\Acl as AclClass,
use Zend\Session\Container as SessionContainer;
use Zend\ServiceManager\Exception;

use Agere\String\Strings as AgereString;
use Agere\User\Controller\Plugin\UserAuthentication as AuthPlugin;
use Agere\User\Acl\Acl as AclClass;
use Agere\User\View\Helper\UserHelper;

/**
 * Authentication Event Handler Class
 *
 * This Event Handles Authentication
 *
 * @category  User
 * @package   User_Event
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/home/type:new-bsd New BSD License
 */
class Authentication
{
	/**
	 * @var AuthPlugin
	 */
	protected $_userAuth = null;

	/**
	 * @var AclClass
	 */
	protected $_aclClass = null;

	protected $roles;

	protected $adapter;

	protected $accessDefault = 6;
	protected $denyDefault = 0;

	/**
	 * Initialization ACL resources for current user
	 */
	public function init() {
		$this->initAcl();
		//$this->basicAuthentication();
	}


	public function basicAuthentication($login, $password) {
		//\Zend\Debug\Debug::dump([$login, $password]);die(__METHOD__);
		if ($login && $password) {
			//$query = "SELECT authentication_id FROM authentication WHERE username = ? AND password = ?";
			//add your own auth code here. I have it check against a database table and return a value if found.

			//$sm = $this->getUserAuthenticationPlugin()->getConroller()->getServiceLocator();
			//$request = $sm->get('Request');

			$uAuth = $this->getUserAuthenticationPlugin(); //@FIXME improve realisation
			$authService = $uAuth->getAuthService();

			/** @var \Agere\Agere\Authentication\Adapter\DbTable\CredentialTreatmentAdapter $authAdapter */
			$authAdapter = $uAuth->getAuthAdapter();


			$email = $login;
			$passwordHash = \Agere\User\Service\UserService::getHashPassword($password);
			$authAdapter->setIdentity($email);
			$authAdapter->setCredential($passwordHash);
			$authAdapter->setWhere(['remove' => [0]]);

			/** @var \Zend\Authentication\Result $result */
			$result = $authService->authenticate($authAdapter);

			//\Zend\Debug\Debug::dump($result->getMessages()); die(__METHOD__);

			if ($result->isValid()) {
				return true;
			} else {
				throw new \SOAPFault("Incorrect username and or password.", 401);

			}

		} else {
			throw new \SOAPFault("Invalid username and password format. Values may not be empty and are case-sensitive.", 401);

		}
	}

	/**
	 * @throws Exception\RuntimeException Warning! This method must be called only once
	 */
	private function initAcl(/*MvcEvent $e*/) {
		/*if ($this->roles) {
			throw new Exception\RuntimeException('ACL initialization must be only once');
		}*/
		foreach ($this->roles as $role => $resources) {
			$role = new \Zend\Permissions\Acl\Role\GenericRole($role);
			$this->_aclClass->addRole($role);
			//adding resources
			foreach ($resources as $resource) {
				if (!$this->_aclClass->hasResource($resource['target'])) {
					//\Zend\Debug\Debug::dump([$resource['target'], /*$this->roles, __METHOD__*/]);
					$this->_aclClass->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource['target']));
				}
				if ($resource['access'] == $this->denyDefault) {
					$this->_aclClass->deny($role, $resource['target'], $resource['access']);
				} else {
					$this->_aclClass->allow($role, $resource['target'], $resource['access']);
				}
			}
		}
		//unset($this->roles);
		//setting to view
		//$e->getViewModel()->acl = $this->acl;
	}


	public function mvcPreDispatch($event) {
		//\Zend\Debug\Debug::dump([$this->roles, spl_object_hash($this), __METHOD__]);
		$params = $event->getRouteMatch()->getParams();

		// Access to page
		$result = $this->preDispatch($event);
		$this->getDbPage($event, $params); // here set $permissionDenied


		return $result;
	}

	/**
	 * preDispatch Event Handler
	 *
	 * @param \Zend\Mvc\MvcEvent $event
	 * @throws \Exception
	 * @todo Зробити повний рефакторинг прав доступу. Невідомо чому тут додається $defaultResource...
	 */
	public function preDispatch(MvcEvent $event) {
		static $defaultResource;


		//@todo - Should we really use here and Controller Plugin?
		$userAuth = $this->getUserAuthenticationPlugin();
		$viewModel = $event->getViewModel();
		//$viewModel->permissionDenied = false;
		//$this->_aclClass = $viewModel->acl;

		$role = [AclClass::DEFAULT_ROLE];

		$access = AclClass::getAccess();
		$accessTotal = AclClass::getAccessTotal();

		/**
		 * This method set the session time (60 minutes) for all users except user resource "all"
		 */
		if ($userAuth->hasIdentity()) {
			$user = $userAuth->getIdentity();
            //\Zend\Debug\Debug::dump($user); die(__METHOD__);

			//$role = unserialize($user['mnemo']); //@todo - Get role from user!
			//$resource = unserialize($user['resource']);
            $resource = [];
			foreach ($user->getRoles() as $role) {
				//\Zend\Debug\Debug::dump($role->getResource()); die(__METHOD__);

				$resource[] = $role->getResource();
            }
            $role = $user->getRoles()->first()->getMnemo();
			if (!in_array('all', $resource)) {
				// Update expire login
				$sessionAuth = new SessionContainer('Zend_Auth');
				$sessionAuth->setExpirationSeconds(3600); // 60 minutes
			}

			if (!$defaultResource) {
				// Set default resource
				$defaultResource = ['files/get'];
				foreach ($defaultResource as $target) {
					$this->_aclClass->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($target));
					$this->_aclClass->allow($role, $target, AclClass::getAccessTotal());
				}
			}

		}

		$allowed = [$this->_aclClass->isAllowed($role, 'all', $accessTotal)];
		// Allowed session
		if (isset($_SESSION['location'])) {
			$target = $_SESSION['location']['controller'] . '/' . $_SESSION['location']['action'];
			// Allowed
			if ($this->_aclClass->hasResource($target)) {

				$allowed[] = $this->_aclClass->isAllowed($role, $target, $accessTotal);
				$allowed[] = $this->_aclClass->isAllowed($role, $target, $access['write']);
				$allowed[] = $this->_aclClass->isAllowed($role, $target, $access['read']);
			}

			if (in_array(true, $allowed)) {
				$dataUrl = [
					'controller' => $_SESSION['location']['controller'],
					'action'     => $_SESSION['location']['action'],
				];
				if (isset($_SESSION['location']['id'])) {
					$dataUrl['id'] = $_SESSION['location']['id'];
				}
				$url = $event->getRouter()->assemble($dataUrl, ['name' => 'default/id']);
				$response = $event->getResponse();
				$response->getHeaders()->addHeaderLine('Location', $url);
				$response->setStatusCode(302);
				$response->sendHeaders();
				unset($_SESSION['location']);
				exit;
			}
		}


		// Resource
		$routeMatch = $event->getRouteMatch();
		$controller = $routeMatch->getParam('controller');
		$action = $routeMatch->getParam('action');
		$target = $controller . '/' . $action;
		// Allowed
		if ($this->_aclClass->hasResource($target)) {
			$allowed[] = $this->_aclClass->isAllowed($role, $target, $accessTotal);
			$allowed[] = $this->_aclClass->isAllowed($role, $target, $access['write']);
			$allowed[] = $this->_aclClass->isAllowed($role, $target, $access['read']);
		}

		//\Zend\Debug\Debug::dump([$role, $target, $allowed, __METHOD__]); die(__METHOD__);

		if (!in_array(true, $allowed)) {

			if ($userAuth->hasIdentity()) {
				/*$url = $event->getRouter()->assemble(array(
					'controller' => 'index',
					'action'     => 'index',
				), array('name' => 'default'));*/
				$event->stopPropagation(true); // very important string
				//$viewModel->permissionDenied = false;

				return false;
			} else {
				$_SESSION['location'] = $routeMatch->getParams();

				$url = $event->getRouter()->assemble(array(
					'controller' => 'user',
					'action'     => 'login',
				), array('name' => 'default'));
			}

			if ($url != '') {
				$response = $event->getResponse();
				$response->getHeaders()->addHeaderLine('Location', $url);
				$response->setStatusCode(302);
				$response->sendHeaders();
				exit;
			}
		}

	}


	/**
	 * @todo: Implement more perfect structure
	 * @param $dbAdapter
	 * @return mixed
	 */
	public function getDbRoles($dbAdapter) {

		static $accessDefault = 6;

		$sql = <<<SQL
SELECT p.`target`, pa.`maskId`, pa.`access`
FROM `permission_access` pa
LEFT JOIN `permission` p ON pa.`permissionId` = p.`id`
WHERE p.`moduleId` = 0 AND p.`parent` = 0
SQL;
		$results = $dbAdapter->query($sql, $dbAdapter::QUERY_MODE_EXECUTE);
		// making the roles array
		$this->roles['guest'][] = ['target' => 'user/login', 'access' => $accessDefault];
		$this->roles['guest'][] = ['target' => 'user/forgot-password', 'access' => $accessDefault];
		// Table roles to array

		$resultRolesArray = $this->getResultRolesArray($dbAdapter);
		foreach ($results as $result) {

			// Parse maskId
			$assocDigit = UserHelper::parseAccessMask($result['maskId']); // помилка десь тут
			//\Zend\Debug\Debug::dump([$result, $assocDigit]); die(__METHOD__);// локально зараз гляну
			if ($assocDigit['field'] == 'role' && isset($resultRolesArray[$assocDigit['id']])) {
				$this->roles[$resultRolesArray[$assocDigit['id']]['mnemo']][] = [
					'target' => $result['target'],
					'access' => $result['access'],
				];
			}
		}
		//die();
		//\Zend\Debug\Debug::dump($this->roles); die();
		//die(__METHOD__);
		return $this->roles;
	}

	private function getResultRolesArray($dbAdapter) {
		static $resultRolesArray;
		static $accessDefault = 6;

		if (!$resultRolesArray) {
			// Table roles
			$resultRoles = $dbAdapter->query(
				'SELECT r.`id`, r.`mnemo`, r.`resource`FROM `role` r',
				$dbAdapter::QUERY_MODE_EXECUTE
			);

			foreach ($resultRoles as $result) {
				//\Zend\Debug\Debug::dump($resultRoles); die(__METHOD__);
				if ($result['resource'] == 'all') {
					$this->roles[$result['mnemo']][] = [
						'target' => $result['resource'],
						'access' => $accessDefault,
					];
				} else {
					$resultRolesArray[$result['id']] = $result;
				}
			}
		}

		return $resultRolesArray;
	}

	public function getDbPage(MvcEvent $e, $params) {
        $userAuth = $this->getUserAuthenticationPlugin();
        if (!$userAuth->hasIdentity()) {
            return false;
        }

		static $accessDefault = 6;

		$sm = $e->getApplication()->getServiceManager();
		$dbAdapter = $this->getDbAdapter();
		$where = '';

		//ini_set('display_errors', 'on');
		//error_reporting(-1);
		/** @var UserHelper $userHelper */
		$userHelper = $sm->get('ViewHelperManager')->get('user');
		//$simpler = $sm->get('ControllerPluginManager')->get('simpler');
		$user = $userHelper->current();
		//$user = $userHelper->current();
		$viewModel = $e->getViewModel();

		// Acl class
		//$aclClass = $viewModel->acl;
        $roleMnemo = $user->getRoles()->first()->getMnemo();

		$aclClass = $this->getAclClass();
		if ($roleMnemo && !$aclClass->isAllowed($roleMnemo, 'all', $accessDefault)) {
			// Where
			if (isset($params['id']) && $params['id'] > 0) {
				$where = "(p.`target` = '{$params['controller']}/{$params['action']}/{$params['id']}'
						AND p.`moduleId` = {$params['id']} AND p.`type` = 'action')";
			}
			if (isset($params['parent']) && $params['parent'] > 0) {
				if ($where != '') {
					$where .= ' OR ';
				}
				$where .= "(p.`target` = '{$params['controller']}' AND p.`type` = 'controller' AND p.`parent` = {$params['parent']})";
			}

			if ($where != '') {
				// Table permission
				$permissionId = 0;
				$resultPermission = $dbAdapter->query(
					"SELECT p.`id` FROM `permission` p WHERE {$where}",
					$dbAdapter::QUERY_MODE_EXECUTE
				);

				foreach ($resultPermission as $result) {
					$permissionId = $result['id'];
				}
				if ($permissionId > 0) {
					//$maskId = AgereString::getStringAssocDigit($user['maskId'], 'role');
					//$maskId = implode(', ', $maskId);
					//$userId = AgereString::getStringAssocDigit($user['id'], 'user');
					//$currentUser = $userHelper->current();
					//$maskId = AgereString::getStringAssocDigit($simpler($currentUser->getRole())->asArray('id'), 'role');
					$maskId = $userHelper->getAccessMask($user->asArray('role'), 'role');
					$maskId = implode(', ', $maskId);
					//$userId = AgereString::getStringAssocDigit($currentUser->getId(), 'user');
					$userId = $userHelper->getAccessMask($user->getId(), 'user');

					//$maskId = $simpler($user->getRole())->asArray('id');
					//$maskId = implode(', ', $maskId);
					//$userId = AgereString::getStringAssocDigit($user['id'], 'user');

					$sql = <<<SQL
SELECT pa.`maskId`, pa.`access`
FROM `permission_access` pa
WHERE pa.`permissionId` = {$permissionId}
AND (pa.`maskId` IN ({$maskId})
OR pa.`maskId` = '{$userId}')
SQL;
					//\Zend\Debug\Debug::dump($sql); die(__METHOD__);
					// Table permission_access
					$resultAccess = $dbAdapter->query($sql, $dbAdapter::QUERY_MODE_EXECUTE);
					// Access to page

					if (!$resultAccess->count()) {
						//$viewModel->permissionDenied = false;
					}
				}
			}
		}
	}

	/**
	 * Sets Authentication Plugin
	 *
	 * @param \Agere\User\Controller\Plugin\UserAuthentication $userAuthenticationPlugin
	 * @return Authentication
	 */
	public function setUserAuthenticationPlugin(AuthPlugin $userAuthenticationPlugin) {
		$this->_userAuth = $userAuthenticationPlugin;

		return $this;
	}

	/**
	 * Gets Authentication Plugin
	 *
	 * @return \Agere\User\Controller\Plugin\UserAuthentication
	 */
	public function getUserAuthenticationPlugin() {
		return $this->_userAuth;
	}


	/**
	 * Sets ACL Class
	 *
	 * @param AclClass $aclClass
	 * @return $this
	 */
	public function setAclClass(AclClass $aclClass)
	{
		$this->_aclClass = $aclClass;

		return $this;
	}

	/**
	 * Gets ACL Class
	 *
	 * @return \Zend\Permissions\Acl\Acl
	 */
	public function getAclClass()
	{
		if ($this->_aclClass === null) {
			$this->_aclClass = new AclClass([]);
		}

		return $this->_aclClass;
	}

	public function setRoles(array $roles) {
		$this->roles = $roles;

		return $this;
	}

	public function getRole() {
		return $this->roles;
	}

	public function setDbAdapter($adapter) {
		$this->adapter = $adapter;
	}

	public function getDbAdapter() {
		return $this->adapter;
	}

}
