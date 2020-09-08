<?php

namespace ZnBundle\User\Domain\Entities;

use yii2rails\domain\BaseEntity;

class SecurityEntity {

	private $id;
    private $identity_id;
    private $password_hash;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getIdentityId()
    {
        return $this->identity_id;
    }

    public function setIdentityId($identity_id): void
    {
        $this->identity_id = $identity_id;
    }

    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    public function setPasswordHash($password_hash): void
    {
        $this->password_hash = $password_hash;
    }

	/*public function setPassword($password) {
		$this->password_hash = \Yii::$app->security->generatePasswordHash($password);
	}
	
	public function isValidPassword($password) {
		return \Yii::$app->security->validatePassword($password, $this->password_hash);
	}*/
}
