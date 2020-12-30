<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Libs\EntityManager;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnLib\Db\Capsule\Manager;
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
    protected $assignmentRepository;
    protected static $entityClass;

    public function __construct(
        EntityManager $em,
        Manager $capsule,
        AssignmentRepository $assignmentRepository
    )
    {
        parent::__construct($em, $capsule);
        $this->assignmentRepository = $assignmentRepository;
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
