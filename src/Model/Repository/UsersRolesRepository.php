<?php
namespace Agere\User\Model\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\EntityRepository;

use Agere\User\Model\UsersRoles;
use Agere\Role\Model\Role;
class UsersRolesRepository extends EntityRepository {

	protected $_table = 'user';
	protected $_alias = 'u';
	protected $roleAlias = 'roles';


	public function getUsersRoles()
	{
		$queryBuilder = $this->createQueryBuilder('c');

		$queryBuilder->select(array('c', 'u'))
			->leftJoin('c.user', 'u');

		return $queryBuilder->getQuery()->getResult();
	}

	public function getUsers2()
	{
		$qb = $this->createQueryBuilder('user');
		$qb->select('ur');
		$qb->select('rol')
			->from(UsersRoles::class, 'ur')
			//->from(Role::class, 'rol')
			//->from(Role::class, 'rol')
			//->from(Role::class, 'r')
			->leftJoin('ur.user', 'ds')
			//->leftJoin('ds.id', 'dds')
			//->leftJoin('rol.mnemo')
			//->leftJoin('r.mnemo', 'u')
		;
		//$qb->where($qb->expr()->in('rol' . '.id', '?1'));
		//$qb->setParameter(1, 'ur.roleId');

		/*$qb
			->select('a', 'u')
			->from('Credit\Entity\UserCreditHistory', 'a')
			->leftJoin('a.user', 'u')
			->where('u = :user')
			->setParameter('user', $users)
			->orderBy('a.created_at', 'DESC');*/


		return $qb;

	}
	
	/*=================Old code =============================================*/

	
	public function findByRoles($roles) {
		$roleAlias = 'roles';

		$qb = $this->createQueryBuilder($this->_alias);
		$qb->select($this->_alias)
			->addSelect($roleAlias)
			//->leftJoin($this->_alias . '.roles', $roleAlias, 'WITH', $qb->expr()->in($roleAlias . '.id', '?1'))
			->leftJoin($this->_alias . '.role', $roleAlias)
		;

		$qb->where($qb->expr()->in($roleAlias . '.id', '?1'));
		$qb->setParameter(1, $roles);

		//$query = $qb->getQuery();
		//\Zend\Debug\Debug::dump($query->getSql()); die(__METHOD__);

		return $qb;
	}

	/**
	 * @param string $where
	 * @param array $args
	 * @param array $orderBy
	 * @param array $groupBy
	 * @param string $distinct
     * @param bool $isPermission
	 * @return array
	 */
	public function findUsers($where = '', array $args = [], array $orderBy = [], array $groupBy = [], $distinct = '', $isPermission = false)
	{
		$rsm = new ResultSetMapping();

		$rsm->addScalarResult('id', 'id');
		$rsm->addScalarResult('email', 'email');
		$rsm->addScalarResult('password', 'password');
		$rsm->addScalarResult('firstName', 'firstName');
		$rsm->addScalarResult('lastName', 'lastName');
		$rsm->addScalarResult('patronymic', 'patronymic');
		$rsm->addScalarResult('phone', 'phone');
		$rsm->addScalarResult('phoneWork', 'phoneWork');
		$rsm->addScalarResult('phoneInternal', 'phoneInternal');
		$rsm->addScalarResult('post', 'post');
		$rsm->addScalarResult('dateEmployment', 'dateEmployment');
		$rsm->addScalarResult('dateBirth', 'dateBirth');
		$rsm->addScalarResult('photo', 'photo');
		$rsm->addScalarResult('showIndex', 'showIndex');
		$rsm->addScalarResult('notation', 'notation');
		$rsm->addScalarResult('cityId', 'cityId');
		$rsm->addScalarResult('maskId', 'maskId');
		$rsm->addScalarResult('department', 'department');
		$rsm->addScalarResult('city', 'city');
		$rsm->addScalarResult('company', 'company');
		$rsm->addScalarResult('role', 'role');
		$rsm->addScalarResult('mnemo', 'mnemo');
		$rsm->addScalarResult('resource', 'resource');
		$rsm->addScalarResult('supplier', 'supplier');

		if ($distinct != '')
		{
			$distinct = 'DISTINCT '.$distinct.', ';
		}

        $join = '';

        if ($isPermission)
        {
            $join = "INNER JOIN `permission_access` pa ON pa.`maskId`= CONCAT({$this->_alias}.`id`, '00')
                    INNER JOIN `permission` p ON pa.`permissionId` = p.`id`";
        }

		$order = $this->getOrderBy($orderBy);

		if ($order != '')
		{
			$order = "ORDER BY {$order}";
		}

		$group = '';

		if ($groupBy)
		{
			$group = 'GROUP BY '.implode(', ', $groupBy);
		}

		$query = $this->_em->createNativeQuery(
			"SELECT {$distinct} `{$this->_alias}`.*, d.`name` AS department, c.`city`, c.`name` AS company, r.`role`, r.`mnemo`,
			r.`resource`, uc.`cityId`, ur.`maskId`, s.`name` AS supplier
			FROM {$this->_table} {$this->_alias}
			LEFT JOIN `department` d ON {$this->_alias}.`departmentId` = d.`id`
			LEFT JOIN `users_cities` uc ON {$this->_alias}.`id` = uc.`userId`
			LEFT JOIN `city` c ON uc.`cityId` = c.`id`
			LEFT JOIN `users_roles` ur ON {$this->_alias}.`id` = ur.`userId`
			LEFT JOIN `roles` r ON ur.`maskId` = r.`id`
			LEFT JOIN `supplier` s ON {$this->_alias}.`supplierId` = s.`id`
			{$join}
			WHERE 1 > 0 {$where}
			{$order}
			{$group}",
			$rsm
		);

		if ($args)
		{
			$query = $this->setParametersByArray($query, $args);
		}

		return $query;
	}

	/**
	 * @param null|int $remove
	 * @return mixed
	 */
	public function findAllCollection($remove = null)
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$where = '';
		$data = [];

		if (! is_null($remove))
		{
			$where .= "WHERE {$this->_alias}.`remove` = ?";
			$data[] = $remove;
		}

		$query = $this->_em->createNativeQuery(
			"SELECT *
			FROM {$this->_table} {$this->_alias}
			{$where}",
			$rsm
		);

		if ($data)
		{
			$query = $this->setParametersByArray($query, $data);
		}

		return $query->getResult();
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @param null|int $remove
	 * @return mixed
	 */
	public function findById($id, $field = 'id', $remove = null)
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$where = '';
		$data[] = $id;

		if (! is_null($remove))
		{
			$where .= "AND {$this->_alias}.`remove` = ?";
			$data[] = $remove;
		}

		$query = $this->_em->createNativeQuery(
			"SELECT *
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`$field` = ? {$where}
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, $data);

		$result = $query->getResult();

		if (count($result) == 0)
		{
			$result = $this->createOneItem();
		}
		else
		{
			$result = $result[0];
		}

		return $result;
	}

}