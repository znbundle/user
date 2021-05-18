<?php

namespace ZnBundle\User\Yii2\Entities;

use Illuminate\Container\Container;
use yii\web\IdentityInterface;
use yii2bundle\account\domain\v3\entities\SecurityEntity;
use yii2bundle\rbac\domain\entities\AssignmentEntity;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yii2rails\domain\data\Query;
use yii2rails\domain\values\TimeValue;
use yubundle\staff\domain\v1\entities\CompanyEntity;
use yubundle\user\domain\v1\entities\PersonEntity;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class LoginEntity extends IdentityEntity implements IdentityInterface {

	public static function findIdentity($id) {
        /** @var IdentityRepositoryInterface $repository */
        $repository = Container::getInstance()->get(IdentityRepositoryInterface::class);
        return $repository->oneById($id);
	}
	
	public static function findIdentityByAccessToken($token, $type = null) {
	}
	
	public function getId() {
		return intval($this->id);
	}

	public function getAuthKey() {
		
	}
	
	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}
}
