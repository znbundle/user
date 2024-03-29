<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use App\Organization\Domain\Interfaces\Repositories\LanguageRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Relations\IdentityRelation;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Base\Domain\Mappers\TimeMapper;
use ZnUser\Rbac\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;
use ZnUser\Rbac\Domain\Interfaces\Repositories\RoleRepositoryInterface;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';
    protected $entityClass;
    protected $identityRelation;

    public function __construct(
        EntityManagerInterface $em,
        Manager $capsule,
        IdentityRelation $identityRelation
    )
    {
        parent::__construct($em, $capsule);
        $entity = $this->getEntityManager()->createEntity(IdentityEntityInterface::class);
        $this->entityClass = get_class($entity);
        $this->identityRelation = $identityRelation;
    }

    public function mappers(): array
    {
        return [
            new TimeMapper(['created_at', 'updated_at'])
        ];
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function relations2()
    {
        return $this->identityRelation->relations();
        /*return [
            [
                'class' => OneToOneRelation::class,
                'relationAttribute' => 'id',
                'relationEntityAttribute' => 'credential',
                'foreignRepositoryClass' => CredentialRepositoryInterface::class,
                'foreignAttribute' => 'identity_id'
            ],
            [
                'class' => OneToManyRelation::class,
                'relationAttribute' => 'id',
                'relationEntityAttribute' => 'assignments',
                'foreignRepositoryClass' => AssignmentRepositoryInterface::class,
                'foreignAttribute' => 'identity_id'
            ],
        ];*/
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
