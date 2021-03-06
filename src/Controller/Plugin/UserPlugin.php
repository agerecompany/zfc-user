<?php
/**
 * User Plugin
 *
 * @category Agere
 * @package Agere_Users
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 11.02.2016 15:08
 */
namespace Agere\User\Controller\Plugin;

use Zend\Stdlib\Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

use Agere\User\Acl\Acl;
use Agere\User\Service\UserService;
use Agere\User\Model\User as UserModel;
use Agere\Simpler\Plugin\Simpler;

class UserPlugin extends AbstractPlugin {
	/** @var UserService */
	protected $userService;

	/** @var AuthenticationService  */
	protected $authService;

    /** @var Acl */
	protected $acl;
	
	/** @var Simpler */
	protected $simpler;

	/**
	 * @param AuthenticationService $authService
	 * @param UserService $userService
	 * @param Acl $acl
	 */
	public function __construct(
        AuthenticationService $authService,
        UserService $userService,
        Acl $acl
    ) {
		$this->authService = $authService;
		$this->userService = $userService;
		$this->acl = $acl;
	}
	
	public function setSimpler($simpler)
	{
		$this->simpler = $simpler;
	}

	/**
	 * @return mixed|null
	 * @deprecated
	 */
	public function getIdentity() {
		return $this->getAuthService()->getIdentity();
	}

	public function hasIdentity() {
		return $this->authService->hasIdentity();
	}

	public function isAdmin() {
		//\Zend\Debug\Debug::dump($this->current()->getRoles()->first()->getResource()); die(__METHOD__);

		return $this->current()->getRoles()->first()->getResource() == 'all';
	}

    public function hasAccess($resource) {
        $user = $this->current();
        $role = $user->getRoles()->first()->getMnemo();

        $allowed = ['all' => $this->acl->isAllowed($role, 'all', Acl::getAccessTotal())];
        if ($this->acl->hasResource($resource)) {
            $allowed['total'] = $this->acl->isAllowed($role, $resource, Acl::getAccessTotal());
            $allowed['write'] = $this->acl->isAllowed($role, $resource, Acl::getAccess()['write']);
            $allowed['read'] = $this->acl->isAllowed($role, $resource, Acl::getAccess()['read']);
        }

        //\Zend\Debug\Debug::dump($allowed); die(__METHOD__);
        if (in_array(true, $allowed)) {
            return true;
        }

        return false;
    }

	public function getUserService() {
		return $this->userService;
	}

	public function getAuthService() {
		return $this->authService;
	}

	public function getAcl() {
		return $this->acl;
	}

    /**
     * @return UserModel
     */
	public function current() {
		static $user;
		if (!$user && $currentUser = $this->getAuthService()->getIdentity()) {
			//if ($currentUser = $this->getAuthService()->getIdentity()) {
				$user = $this->getUserService()->find($currentUser->getId());
			//}
			//\Zend\Debug\Debug::dump($currentUser); die(__METHOD__);
			//$user = $this->getUserService()->find($currentUser['id']);
        }

		return $user;
	}

    public function asString($collectionName, $field = Simpler::DEFAULT_FIELD) {
		$user = $this->current();
		
		if (!method_exists($user, $method = 'get' . ucfirst($collectionName))) {
			throw new Exception\RuntimeException(sprintf('Method for retrieve "%s" collection not exist', $collectionName));
		}
		
		// if thrown exception then inject simpler plugin
		$collection = $user->{$method}();
		return $this->simpler->setContext($collection)->asString($field);
    }

    public function asArray($collectionName, $field = Simpler::DEFAULT_FIELD) {
        $user = $this->current();
		
		if (!method_exists($user, $method = 'get' . ucfirst($collectionName))) {
			throw new Exception\RuntimeException(sprintf('Method for retrieve "%s" collection not exist', $collectionName));
		}
		
		// if thrown exception then inject simpler plugin
		$collection = $user->{$method}();
		return $this->simpler->setContext($collection)->asArray($field);
    }

    public static function getAccessMask($args, $assocDigit) {
        $assocDigits = [
            'user'  => '%s00',
            'role'  => '0%s0',
            'group' => '00%s',
        ];

        if (!isset($assocDigits[$assocDigit])) {
            return $args;
        }
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                $args[$key] = sprintf($assocDigits[$assocDigit], $val);
            }
        } else {
            $args = sprintf($assocDigits[$assocDigit], $args);
        }

        return $args;
    }

	public static function parseAccessMask($str, $assocDigits = ['user', 'role', 'group'])
	{
		$countDigits = count($assocDigits);
		$tmpCountDigits = $countDigits;
		$assocDigit = '';
		$toInt = '';
		for ($i = 0, $k = strlen($str); $i < $k; ++$i) {
			$remainder = $k - $i;
			if ($str[$i] == 0 && $toInt == '') {
				--$tmpCountDigits;
			} else if ($str[$i] > 0 OR ($toInt != '' && $remainder >= $tmpCountDigits)) {
				$toInt .= $str[$i];
				if ($remainder == $tmpCountDigits) {
					$j = $countDigits - $tmpCountDigits;
					$assocDigit = $assocDigits[$j];
					break;
				}
			}
		}

		return [
			'id' => (int) $toInt,
			'field' => $assocDigit,
		];
	}

	/**
	 * @param string $name
	 * @return string
	 * @throws Exception\RuntimeException
	 */
	/*public function run() {
		if (method_exists($this, $method = 'current' . ucfirst($name))) {
			return $this->{$method}();
		}

		throw new Exception\RuntimeException(sprintf(
			'Option with name %s is not supported. Allowed values: module, controller, action, router, route, request, view',
			$name
		));
	}*/

	protected function getSm() {
		return $this->getController()->getServiceLocator();
	}

	/*public function __invoke(...$args) {
		if (!$args) {
			return $this;
		}

		$name = isset($args[0]) ? $args[0] : false;
		!isset($args[1]) || $this->setContext($args[1]);

		return $this->run($name);
	}*/

}