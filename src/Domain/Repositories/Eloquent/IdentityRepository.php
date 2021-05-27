<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use Illuminate\Container\EntryNotFoundException;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Libs\Query;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnLib\Db\Capsule\Manager;
use ZnSandbox\Sandbox\Casbin\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';
    protected static $entityClass;

    public function __construct(
        EntityManagerInterface $em,
        Manager $capsule
    )
    {
        parent::__construct($em, $capsule);
    }

    public function getEntityClass(): string
    {
        if (empty(static::$entityClass)) {
            try {
                $entity = $this->getEntityManager()->createEntity(IdentityEntityInterface::class);
//                $entity = $this->container->get(IdentityEntityInterface::class);
                static::$entityClass = get_class($entity);
            } catch (EntryNotFoundException $e) {
                static::$entityClass = IdentityEntity::class;
            }
        }
        return static::$entityClass;
    }

    public function relations2()
    {
        return [
            [
                'class' => OneToManyRelation::class,
                'relationAttribute' => 'id',
                'relationEntityAttribute' => 'roles',
                'foreignRepositoryClass' => AssignmentRepositoryInterface::class,
                'foreignAttribute' => 'identity_id',
            ],
        ];
    }

    public function findUserByUsername(string $username, Query $query = null): IdentityEntityInterface
    {
        return $this->findUserBy(['username' => $username], $query);
    }

    private function findUserBy(array $condition, Query $query = null): IdentityEntityInterface
    {
        $query = Query::forge($query);
        $query->whereFromCondition($condition);
        return $this->one($query);
    }
}
