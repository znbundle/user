<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use ZnBundle\Eav\Domain\Interfaces\Repositories\EnumRepositoryInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Libs\Relation\OneToMany;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnLib\Db\Capsule\Manager;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';
    protected $assignmentRepository;
    protected static $entityClass;

    public function __construct(
        EntityManagerInterface $em,
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

    public function relations2()
    {
        return [
            [
                'class' => OneToManyRelation::class,
                'relationAttribute' => 'id',
                'name' => 'roles',
                'relationEntityAttribute' => 'assignments',
                'foreignRepositoryClass' => AssignmentRepository::class,
                'foreignAttribute' => 'user_id',
            ],
            /*'roles' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToMany;
                    $m2m->selfModel = $this;
                    $m2m->foreignModel = $this->assignmentRepository;
                    $m2m->selfField = 'userId';
                    $m2m->foreignContainerField = 'assignments';
                    $m2m->run($collection);
                },
            ],*/
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
