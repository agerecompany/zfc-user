<?php
namespace Agere\User\Service;

use Agere\Core\Service\DomainServiceAbstract;
use Agere\User\Model\User as UsersModel;
use Agere\User\Model\UsersRoles;

class UsersRolesService extends DomainServiceAbstract
{
	//protected $_repositoryName = 'usersRoles';
	protected $entity = UsersRoles::class;


	/**
	 * @param int|array $id
	 * @param string $field
	 * @return mixed
	 */
	public function getItems($id, $field = 'id')
	{
		/** @var \Magere\Users\Model\Repository\UsersRolesRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findItems($id, $field);
	}

	/**
	 * @param int|array $id
	 * @param string $field
	 * @return array
	 */
	public function getItemsRoles($id, $field = 'id')
	{
		/** @var \Magere\Users\Model\Repository\UsersRolesRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findItemsRoles($id, $field);
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @return array
	 */
	public function getRoleIdItemsArray($id, $field = 'id')
	{
		$result = [];
		$items = $this->getItems($id, $field);

		/** @var \Magere\Users\Model\UsersRoles $item */
		foreach ($items as $item)
		{
			$result[] = $item->getRoleId();
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @param UsersModel $user
	 */
	public function saveData(array $data, UsersModel $user)
	{
		/** @var \Magere\Users\Model\Repository\UsersRolesRepository $repository */
		//$repository = $this->getRepository($this->_repositoryName);

		$i = 0;
		//$items = $this->getItems($user->getId(), 'userId');
		$items = $user->getRoles();

		/** @var \Magere\Users\Model\UsersRoles $item */
		foreach ($items as $item)
		{
			if (isset($data['roleId'][$i])) {
				//$oneItem->setRoleId($data['roleId'][$i]);
				//$item->setId($data['roleId'][$i]);
				unset($data['roleId'][$i]);
				//$repository->addItem($item);
				$this->getEntityManager()->persist($item);

				++ $i;
			}
			else
			{
				//$repository->addRemove($item);
				$this->getEntityManager()->remove($item);
				$ids[] = $item->getId();
			}
		}

		/*if (isset($data['roleId'])) {
			foreach ($data['roleId'] as $val) {
				$item = $repository->createOneItem();
				//$item->setId($val);
				$item->setUsers($user);
				$item->setUserId($user->getId());
				//$repository->addItem($item);
				$this->getEntityManager()->persist($item);
			}

			//$user->setRoles();
		}
		$repository->saveData();*/

		$this->getEntityManager()->flush();
	}

}