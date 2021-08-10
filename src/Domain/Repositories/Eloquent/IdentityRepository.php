<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnLib\Db\Capsule\Manager;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';
    protected $entityClass;

    public function __construct(
        EntityManagerInterface $em,
        Manager $capsule
    )
    {
        parent::__construct($em, $capsule);
        $entity = $this->getEntityManager()->createEntity(IdentityEntityInterface::class);
        $this->entityClass = get_class($entity);
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function relations2()
    {
        return [
            [
                'class' => OneToOneRelation::class,
                'relationAttribute' => 'id',
                'relationEntityAttribute' => 'credential',
                'foreignRepositoryClass' => CredentialRepositoryInterface::class,
                'foreignAttribute' => 'identity_id'
            ]
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
