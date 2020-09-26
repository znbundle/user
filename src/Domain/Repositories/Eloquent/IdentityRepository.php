<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
use ZnCore\Db\Db\Helpers\Manager;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Libs\Relation\OneToMany;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';
    protected $container;
    protected $assignmentRepository;
    protected static $entityClass;

    public function __construct(
        Manager $capsule,
        ContainerInterface $container,
        AssignmentRepository $assignmentRepository
    )
    {
        parent::__construct($capsule);
        $this->container = $container;
        $this->assignmentRepository = $assignmentRepository;
    }

    public function getEntityClass(): string
    {
        if (empty(static::$entityClass)) {
            try {
                $entity = $this->container->get(IdentityEntityInterface::class);
                static::$entityClass = get_class($entity);
            } catch (EntryNotFoundException $e) {
                static::$entityClass = IdentityEntity::class;
            }
        }
        return static::$entityClass;
    }

    public function relations()
    {
        return [
            'roles' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToMany;
                    $m2m->selfModel = $this;
                    $m2m->foreignModel = $this->assignmentRepository;
                    $m2m->selfField = 'userId';
                    $m2m->foreignContainerField = 'assignments';
                    $m2m->run($collection);
                },
            ],
        ];
    }

    public function findUserByUsername(string $username, Query $query = null): IdentityEntity
    {
        return $this->findUserBy(['login' => $username], $query);
    }

    public function findUserBy(array $condition, Query $query = null): IdentityEntity
    {
        $query = Query::forge($query);
        $query->whereFromCondition($condition);
        return $this->one($query);
    }
}
