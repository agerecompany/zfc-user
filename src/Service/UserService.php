<?php
namespace Agere\User\Service;

use Agere\Core\Service\DomainServiceAbstract;
use Agere\User\Model\User;

class UserService extends DomainServiceAbstract
{
    protected $entity = User::class;

	protected $_repositoryName = 'users';
	protected $_entityAlias = 'Users';
	protected $isMd5 = false;
	const SOLT = 'G6t8?Mj$7h#ju';

	protected $_pathUploadFiles = './var/documents/users/';

	protected $_sizesPhoto = [
		'large'		=> 400,
		'middle'	=> 350,
		'small'		=> 100,
	];

	/**
	 * @var \Agere\Agere\ORM\Tools\Pagination\NativePaginator
	 */
	protected $_paginator;

	/**
	 * @var array, table status
	 */
	protected $_statuses;

	/**
	 * @var array, table fields
	 */
	protected $_fields;

	public function saves(User $user)
	{
		$om = $this->getObjectManager();
		if (!$om->contains($user)) {
			$om->persist($user);
		}
		$om->flush();
	}


	/*=================Old code =============================================*/


	/**
	 * @return string
	 */
	public function getPathUploadFiles()
	{
		return $this->_pathUploadFiles;
	}

	/**
	 * @param string $key
	 * @return array|int
	 */
	public function getSizesPhoto($key = '')
	{
		if ($key && isset($this->_sizesPhoto[$key]))
		{
			return $this->_sizesPhoto[$key];
		}
		else
		{
			return $key ? 0 : $this->_sizesPhoto;
		}
	}

	/**
	 * @param bool $isMd5
	 */
	public function setIsMd5($isMd5)
	{
		$this->isMd5 = $isMd5;
	}

	/**
	 * @return bool
	 */
	public function getIsMd5()
	{
		return $this->isMd5;
	}

	public function getPager()
	{
		return $this->_paginator;
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		if (is_null($this->_fields))
		{
			// Table fields
			/** @var \Agere\Fields\Service\FieldsService $fieldsService */
			$fieldsService = $this->getService('fields');
			$items = $fieldsService->getAllByEntityName('users');
            $this->_fields = $this->toOptions($items, ['name'], '', 'mnemo');
        }

		return $this->_fields;
	}

	/**
	 * @return array
	 */
	public function getStatuses()
	{
		if (is_null($this->_statuses))
		{
			// Table status
			/** @var \Agere\Status\Service\StatusService $statusService */
			$statusService = $this->getService('status');
			$items = $statusService->getItems('users', 'notEmpty');
			$this->_statuses = $this->toOptions($items, ['id'], '', 'mnemo');
		}

		return $this->_statuses;
	}

	/**
	 * @param array $condition, example ['field' => $val, 'field2' => $val2]
	 * @param int $currentPage
	 * @param bool $isLimit
	 * @param null|int $totalItems
	 * @param array $orderBy
	 * @param array $groupBy
	 * @param string $whereStr
	 * @param string $distinct
	 * @param int $perPage
	 * @return mixed
	 */
	public function getItemsCollection(array $condition = [], $currentPage = 0, $isLimit = false, $totalItems = null, array $orderBy = [], array $groupBy = [], $whereStr = '', $distinct = '', $perPage = 36)
	{
		/** @var \Agere\User\Model\Repository\UsersRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		if ($totalItems == null OR $totalItems > 0)
		{
            $isPermission = (isset($condition['parent']));
			$where = $repository->getWhereByArray($condition);
			$query = $repository->findUsers($where['str'].$whereStr, $where['args'], $orderBy, $groupBy, $distinct, $isPermission);
		}

		if ($isLimit)
		{
			if ($totalItems === null)
			{
				$this->_paginator = new NativePaginator($query, $perPage);

				return $this->_paginator->getItems($currentPage);
			}
			else
			{
				$this->_paginator = new NativePaginator(null, $perPage);
				$this->_paginator->setCurrentPage($currentPage);

				return ($totalItems > 0) ? $query->getResult() : [];
			}
		}
		else if (isset($query))
		{
			return $query->getResult();
		}
		else
		{
			return [];
		}
	}

	/**
	 * @param array $condition, example ['field' => $val, 'field2' => $val2]
	 * @param array $fields, example ['field1', 'field2']
	 * @param array $groupBy
	 * @return mixed
	 */
	public function getItemsCollectionArray(array $condition = [], $fields = ['email'], array $groupBy = ['id'])
	{
		$itemsArray = [];
		$items = $this->getItemsCollection($condition, 0, false, null, [], $groupBy);

		foreach ($items as $item)
		{
			$data = [];

			foreach ($fields as $field)
			{
				$data[$field] = $item[$field];
			}

			$itemsArray[$item['id']] = $data;
		}

		return $itemsArray;
	}

	/**
	 * @param array $condition, example [['field' => $val, 'field2' => $val2], ['field' => $val, 'field2' => [$val2]]]
	 * @return mixed
	 */
	public function getItems(array $condition = [])
	{
		/** @var \Agere\User\Model\Repository\UsersRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		$where = '';
		$args = [];

		foreach ($condition as $values)
		{
			$where .= ($where == '') ? ' AND (' : ' OR (';
			$tmp = '';

			foreach ($values as $field => $val)
			{
				if ($tmp != '')
				{
					$tmp .= ' AND ';
				}

				if (is_array($val))
				{
					$idsIn = $repository->getIdsIn($val);
					$tmp .= "`{$field}` IN ({$idsIn})";
					$args = array_merge($args, $val);
				}
				else
				{
					$tmp .= (strpos($field, '.') === false) ? "`{$field}` = ?" : "{$field} = ?";
					$args[] = $val;
				}
			}

			$where .= $tmp.')';
		}

		$query = $repository->findUsers($where, $args, [], ['id']);

		return $query->getResult();
	}

	/**
	 * All items
	 *
	 * @param null|int $remove
	 * @return mixed
	 */
	public function getAllCollection($remove = null)
	{
		/** @var \Agere\User\Model\Repository\UsersRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findAllCollection($remove);
	}

	/**
	 * One item
	 *
	 * @param int $id
	 * @param string $field
	 * @param null|int $remove
	 * @return mixed
	 */
	public function getItem($id, $field = 'id', $remove = null)
	{
		/** @var \Agere\User\Model\Repository\UsersRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findById($id, $field, $remove);
	}

	/**
	 * @param string $password
	 * @return string
	 */
	public static function getHashPassword($password)
	{
		return ($password != '') ? md5($password.self::SOLT) : '';
	}

	public static function generatePassword()
	{
		$password = '';

		$len = 6;
		$str = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
		$strLen = strlen($str) - 1;

		for ($i = 0; $i < $len; ++ $i)
		{
			$j = rand(0, $strLen);
			$password .= $str[$j];
		}

		return $password;
	}

	/**
	 * @param array $data
	 * @param object $oneItem
	 * @return mixed
	 */
	public function save($data, $oneItem)
	{
		if (! $this->isMd5 && isset($data['password']))
		{
			$data['password'] = self::getHashPassword($data['password']);
		}

		if (! $oneItem->getId())
		{
			// Set default value
			if (! isset($data['remove']))
			{
				$data['remove'] = 0;
			}

			if (! isset($data['photo']))
			{
				$data['photo'] = '';
			}

			if (! isset($data['showIndex']))
			{
				$data['showIndex'] = 'city';
			}
		}

		if (isset($data['departmentId']) && ! $data['departmentId'])
		{
			$data['departmentId'] = null;
		}

		foreach ($data as $field => $val)
		{
			if ($field == 'roleId')
			{
				$newField = rtrim($field, 'Id').'s';
				$obj = $this->getService($newField)->getOneItem($val);
				$method = 'set'.ucfirst($newField);
				$oneItem->$method($obj);
			}

			$method = 'set'.ucfirst($field);
			$oneItem->$method($val);
		}

		/** @var \Agere\User\Model\Repository\UsersRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);
		$repository->save($oneItem);

		return $oneItem;
	}

	/**
	 * @param object $user
	 * @return bool
	 */
	public function deleteItem($user)
	{
		if ($user->getId()) {
			$logsItem = $this->getService('logs')->getItemsCollection(['userId' => $user->getId()], '', '', 1);
            $sellingItem = $this->getService('selling')->getOneItem(['soldCarUserId' => $user->getId()]);

			if (!$logsItem->count() && !$sellingItem->getId()) {
				// Table permission_access
				/** @var \Agere\Permission\Service\PermissionAccessService $permissionAccessService */
				$permissionAccessService = $this->getService('permissionAccess');
				$permissionAccessService->deleteByRoleId("{$user->getId()}00");

				// Table users_city
				/** @var \Agere\User\Service\UsersCityService $usersCityService */
				//$usersCityService = $this->getService('usersCity');
                //$usersCityService->saveData(['cityId' => []], $oneItem);
                //die(__METHOD__);
                $user->getCities()->clear();


				// Table users_roles
				/** @var \Agere\User\Service\UsersRolesService $usersRolesService */
				//$usersRolesService = $this->getService('usersRoles');
				//$usersRolesService->saveData(['roleId' => []], $user);
                $user->getRoles()->clear();


                // Table users
				/** @var \Agere\User\Model\Repository\UsersRepository $repository */
				$repository = $this->getRepository($this->_repositoryName);
				$repository->delete($user);

				return true;
			}
		}

		return false;
	}

	/**
	 * @param array $fields
	 * @param \Agere\User\Model\User $item
	 * @param $locator
	 * @return string
	 */
	public function getMessageLog($fields, $item, $locator)
	{
		$allFields = $this->getFields();

		$message = [];

		foreach ($fields as $field)
		{
			if (isset($_POST[$field]) && array_key_exists($field, $allFields))
			{
				$method = 'get'.ucfirst($field);
				$val = $item->$method();

				if (stripos($field, 'date') !== false && is_object($val))
				{
					$val = $val->format('d/m/y');
				}
				else if (in_array($field, ['departmentId', 'supplierId']))
				{
					$val = (int) $val;

					if (! empty($val))
					{
						switch ($field)
						{
							case 'departmentId':
								/** @var \Agere\Department\Service\DepartmentService $departmentService */
								$departmentService = $locator->get('DepartmentService');
								$departmentItem = $departmentService->getOneCollectionBy(['id' => $val]);
								$val = $departmentItem->getName();
								break;
							case 'supplierId':
								/** @var \Agere\Supplier\Service\SupplierService $supplierService */
								$supplierService = $locator->get('SupplierService');
								$supplierItem = $supplierService->getOneItem(['id' => $val]);
								$val = $supplierItem->getName();
								break;
						}
					}
					else
					{
						$val = '';
					}
				}
				else if ($field == 'showIndex')
				{
					$val = $allFields[$val.'Id'];
				}

				$message[] = "{$allFields[$field]}: {$val}";
			}
			else if ($field == 'cityId[]')
			{
				/** @var \Agere\User\Service\UsersCityService $usersCityService */
				//$usersCityService = $locator->get('UsersCityService');
				//$usersCityItems = $usersCityService->getItemsCity(['userId' => $item->getId()]);
				//$cities = $this->toArrayKeyVal('city', $usersCityItems);
				$cities = [];
				foreach ($item->getCities() as $city) {
					$cities[] = $city->getCity();
				}

				$message[] = "{$allFields['cityId']}: ".implode(', ', $cities);
			}
		}

		return $message ? implode('<br>', $message) : '';
	}


	//------------------------------------------Events------------------------------------------
	/**
	 * Module Users
	 *
	 * @param $class
	 * @param $params
	 * @param $name
	 * @return mixed
	 */
	public function delete($class, $params, $name = 'users')
	{
		$event = new LogsEvent();
		return $event->events($class)->trigger($name.'.delete', $this, $params);
	}

	/**
	 * Module Mail
	 *
	 * @param $class
	 * @param $params
	 * @return mixed
	 */
	public function sendMail($class, $params)
	{
		$event = new LogsEvent();
		$event->events($class)->trigger('users.sendMail', $this, $params);
	}

	/**
	 * Module Logs
	 *
	 * @param $class
	 * @param $params
	 */
	public function writeLog($class, $params)
	{
		$event = new LogsEvent();
		$event->events($class)->trigger('users.writeLog', $this, $params);
	}

	/**
	 * Module Files
	 *
	 * @param $class
	 * @param $params
	 * @param $name
	 * @return mixed
	 */
	public function deleteFile($class, $params, $name = 'users')
	{
		$event = new LogsEvent();
		return $event->events($class)->trigger($name.'.deleteFile', $this, $params);
	}

}