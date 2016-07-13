<?php
/**
 * File for Acl Class
 *
 * @category  User
 * @package   User_Acl
 * @author    Marco Neumann <webcoder_at_binware_dot_org>
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/home/type:new-bsd New BSD License
 */

/**
 * @namespace
 */
namespace Agere\User\Acl;

/**
 * @uses Zend\Acl\Acl
 * @uses Zend\Acl\Role\GenericRole
 * @uses Zend\Acl\Resource\GenericResource
 */
use Zend\Permissions\Acl\Acl as ZendAcl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource,
	Zend\Permissions\Acl\Role\RoleInterface,
	Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class to handle Acl
 *
 * This class is for loading ACL defined in a config
 *
 * @category User
 * @package  User_Acl
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/home/type:new-bsd New BSD License
 */
class Acl extends ZendAcl {
    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'guest';

	protected static $_access = [
		'read'	=> 4,
		'write'	=> 2,
	];


    /**
     * Constructor
     *
     * @throws \Exception
     */
    /*public function __construct()
    {
        if (!isset($config['acl']['roles']) || !isset($config['acl']['resources'])) {
            throw new \Exception('Invalid ACL Config found');
        }

        $roles = $config['acl']['roles'];
        if (!isset($roles[self::DEFAULT_ROLE])) {
            $roles[self::DEFAULT_ROLE] = '';
        }

        $this->_addRoles($roles)
             ->_addResources($config['acl']['resources']);
    }*/

	public static function getAccess($key = '')
	{
		return ($key != '' && array_key_exists($key, self::$_access)) ? self::$_access[$key] : self::$_access;
	}

	public static function getAccessTotal()
	{
		return array_sum(self::$_access);
	}

	public static function getAccessForm()
	{
		return [
			'write'	=> self::$_access['write'],
			'all'	=> self::getAccessTotal(),
		];
	}

    /**
     * Adds Roles to ACL
     *
     * @param array $roles
     * @return Acl
     */
    /*protected function _addRoles($roles)
    {
        foreach ($roles as $name => $parent) {
            if (!$this->hasRole($name)) {
                if (empty($parent)) {
                    $parent = array();
                } else {
                    $parent = explode(',', $parent);
                }

                $this->addRole(new Role($name), $parent);
            }
        }

        return $this;
    }*/

    /**
     * Adds Resources to ACL
     *
     * @param $resources
     * @return Acl
     * @throws \Exception
     */
    /*protected function _addResources($resources) {
        foreach ($resources as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if ($controller == 'all') {
                    $controller = null;
                } else {
                    if (!$this->hasResource($controller)) {
                        $this->addResource(new Resource($controller));
                    }
                }

                foreach ($actions as $action => $role) {
                    if ($action == 'all') {
                        $action = null;
                    }

                    if ($permission == 'allow') {
                        $this->allow($role, $controller, $action);
                    } elseif ($permission == 'deny') {
                        $this->deny($role, $controller, $action);
                    } else {
                        throw new \Exception('No valid permission defined: ' . $permission);
                    }
                }
            }
        }

        return $this;
    }*/

	/**
	 * Returns true if and only if the Role has access to the Resource
	 *
	 * The $role and $resource parameters may be references to, or the string identifiers for,
	 * an existing Resource and Role combination.
	 *
	 * If either $role or $resource is null, then the query applies to all Roles or all Resources,
	 * respectively. Both may be null to query whether the ACL has a "blacklist" rule
	 * (allow everything to all). By default, Zend\Permissions\Acl creates a "whitelist" rule (deny
	 * everything to all), and this method would return false unless this default has
	 * been overridden (i.e., by executing $acl->allow()).
	 *
	 * If a $privilege is not provided, then this method returns false if and only if the
	 * Role is denied access to at least one privilege upon the Resource. In other words, this
	 * method returns true if and only if the Role is allowed all privileges on the Resource.
	 *
	 * This method checks Role inheritance using a depth-first traversal of the Role registry.
	 * The highest priority parent (i.e., the parent most recently added) is checked first,
	 * and its respective parents are checked similarly before the lower-priority parents of
	 * the Role are checked.
	 *
	 * @param  RoleInterface|string|array            $role
	 * @param  ResourceInterface|string    $resource
	 * @param  string                               $privilege
	 * @return bool
	 */
    public function isAllowed($role = null, $resource = null, $privilege = null) {
        if (is_array($role)) {
            $isAllowed = false;
            foreach ($role as $val) {
                if ($isAllowed = parent::isAllowed($val, $resource, $privilege)) {
                    break;
                }
            }

            return $isAllowed;
        }

        return parent::isAllowed($role, $resource, $privilege);
    }

}
