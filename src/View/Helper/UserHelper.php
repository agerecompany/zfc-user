<?php
namespace Agere\User\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container as SessionContainer;
use Agere\User\Controller\Plugin\UserPlugin;
use Agere\Simpler\Plugin\Simpler;

class UserHelper extends AbstractHelper implements ServiceLocatorAwareInterface {

	use ServiceLocatorAwareTrait;

	//protected $services;

	//protected $authService;

	/** @var \Agere\User\Service\UserService */
	//protected $_usersService;

	/** @var UserPlugin */
	protected $userPlugin;

	/**
	 * @param UserPlugin $userPlugin
	 * @return $this
	 */
	public function setUserPlugin(UserPlugin $userPlugin) {
		$this->userPlugin = $userPlugin;

		return $this;
	}

	/** @return UserPlugin */
	public function getUserPlugin() {
		if (null === $this->userPlugin) {
			if (!$this->getView()) {
				// Як вирішити? Це значить що десь йде виклик хелперу не через ServiceManager,
				// а на пряму, тобто
				// $user = new \Agere\Users\View\Helper\User($authService);
				// Що робити? Подивитись по Exception де це викликається і замінити на
				// $userHelper = $vhm->get('user');
				// $currentUser = $userHelper->getUser();
				// @see Agere\Fields\View\Helper\Factory\FieldFactory

				throw new \Exception('trp ;p ;');
			}

			$sm = $this->getView()->getHelperPluginManager()->getServiceLocator();
			//$sm = $this->getServiceLocator();
			$this->userPlugin = $sm->get('ControllerPluginManager')->get('user');

		}

		return $this->userPlugin;
	}

	public function current() {
		return $this->getUserPlugin()->current();
	}

	public function asString($collectionName, $field = Simpler::DEFAULT_FIELD) {
        return $this->getUserPlugin()->asString($collectionName, $field);
	}
	
	public function asArray($collectionName, $field = Simpler::DEFAULT_FIELD) {
        return $this->getUserPlugin()->asArray($collectionName, $field);
	}

	public static function getAccessMask($args, $assocDigit) {
        return UserPlugin::getAccessMask($args, $assocDigit);
	}

	public static function parseAccessMask($args, $assocDigit = null) {
		return UserPlugin::parseAccessMask($args, $assocDigit);
	}
	
	/*public function __invoke(...$params) {
		return call_user_func_array($this->getUserPlugin(), $params);
	}*/

	/**
	 * @param AuthenticationService $authService
	 */
	/*public function __construct(AuthenticationService $authService) {
		$this->authService = $authService;
	}*/

	/**
	 * @return bool
	 */
	public function hasIdentity() {
		return $this->getUserPlugin()->hasIdentity();
	}

	/**
	 * @return string
	 */
	public function isAdmin() {
		return $this->getUserPlugin()->isAdmin();
	}

    /**
     * @param string $resource
     * @return bool
     * @throws \Exception
     */
	public function hasAccess($resource) {
		return $this->getUserPlugin()->hasAccess($resource);
	}



	/**
	 * @param string $key
	 * @param Service $service
	 * @deprecated
	 */
	public function addService($key, $service) {
		die('You really have to use: ' . __METHOD__);
		$this->getUserPlugin()->addService($key, $service);
	}

	/**
	 * @return mixed|null
	 * @deprecated
	 */
	public function getUser() {
		$currentUser = $this->getUserPlugin()->getAuthService()->getIdentity();
		if (!is_null($currentUser)) {
			//if (strpos($currentUser['role'], '{') === false) {
			if (!count($currentUser->getRoles())) {
				//\Zend\Debug\Debug::dump($currentUser); die(__METHOD__);
				// Set expire login
				$sessionAuth = new SessionContainer('Zend_Auth');
				$sessionAuth->setExpirationSeconds(0);
			}
			//$currentUser['city'] = unserialize($currentUser['city']);
			//$currentUser['cityId'] = unserialize($currentUser['cityId']);
			//$currentUser['role'] = unserialize($currentUser['role']);
			//$currentUser['roleId'] = unserialize($currentUser['roleId']);
			//$currentUser['mnemo'] = unserialize($currentUser['mnemo']);
			//$currentUser['resource'] = unserialize($currentUser['resource']);
			//$currentUser['brandId'] = unserialize($currentUser['brandId']);
		}

		return $currentUser;
	}

	/**
	 * @return array
	 */
	public function getUserCityOption() {
		$options = [];
		$currentUser = $this->getUser();
		foreach ($currentUser['cityId'] as $key => $val) {
			$options[$val] = $currentUser['city'][$key];
		}

		return $options;
	}

	/**
	 * @param array $fields
	 * @param array $condition
	 * @return mixed
	 */
	public function usersArray($fields = ['email'], $condition = []) {
		return $this->getUserPlugin()->getUserService()->getItemsCollectionArray($condition, $fields);
	}

	/**
	 * @param $valSelected
	 * @param string $title
	 * @param string $titleVal
	 * @param null $cityId
	 * @param array $fields
	 * @param string $formatStr
	 * @param null $remove
	 * @return string
	 * @deprecated
	 */
	protected function _userList($valSelected, $title = '', $titleVal = '0', $cityId = null, $fields = ['email'], $formatStr = '%email', $remove = null) {
		$strOptions = '<option value="' . $titleVal . '">' . $title . '</option>';
		$condition = $cityId ? ['cityId' => $cityId] : [];
		if (!is_null($remove)) {
			$condition['u.remove'] = $remove;
		}
		$users = $this->usersArray($fields, $condition);
		foreach ($users as $id => $user) {
			$selected = ($id == $valSelected) ? ' selected=""' : '';
			$strOptions .= '<option value="' . $id . '"' . $selected . '>' . String::sprintf2($formatStr, $user) . '</option>';
		}

		return $strOptions;
	}

	/**
	 * @param $valSelected
	 * @param string $title
	 * @param string|int $titleVal
	 * @param null|int $cityId
	 * @return string
	 */
	public function userList($valSelected, $title = '', $titleVal = '0', $cityId = null) {
		return $this->_userList($valSelected, $title, $titleVal, $cityId);
	}

	/**
	 * @param $valSelected
	 * @param string $title
	 * @param string|int $titleVal
	 * @param null|int $cityId
	 * @return string
	 */
	public function userFioList($valSelected, $title = '', $titleVal = '0', $cityId = null, $remove = '0') {
		return $this->_userList($valSelected, $title, $titleVal, $cityId, [
			'lastName',
			'firstName',
			'email'
		], '%lastName %firstName (%email)', $remove);
	}

}