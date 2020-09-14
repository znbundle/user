<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityIterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
use ZnCore\Db\Db\Helpers\Manager;
use ZnCore\Domain\Libs\Query;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';
    protected $container;

    public function __construct(Manager $capsule, ContainerInterface $container)
    {
        parent::__construct($capsule);
        $this->container = $container;
    }

    public function getEntityClass(): string
    {
        return get_class($this->container->get(IdentityEntityIterface::class));
    }

    public function findUserByUsername(string $username): IdentityEntity
    {
        return $this->findUserBy(['login' => $username]);
    }

    public function findUserBy(array $condition): IdentityEntity
    {
        $query = new Query;
        $query->whereFromCondition($condition);
        return $this->one($query);
    }
}
