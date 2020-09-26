<?php


namespace ZnBundle\User\Yii2\Entities;

use yii\web\IdentityInterface;
use ZnCore\Base\Traits\MagicAttribute\MagicAttributeTrait;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Helpers\EntityHelper;

class IdentityEntity extends \ZnBundle\User\Domain\Entities\IdentityEntity implements IdentityInterface
{

    use MagicAttributeTrait;

    public $created_at = null;
    private $assignments;

    protected $status = 1;
    //public $roles;
    public $token;
    public $person_id;

    public function getRoles(): array {
        /*if(isset($this->roles)) {
            return $this->roles;
        }*/
        if(!isset($this->assignments)) {
            return [];
        }
        //prr(EntityHelper::getColumn($this->assignments, 'itemName'));
        return EntityHelper::getColumn($this->assignments, 'itemName');
    }

    public function getAssignments()
    {
        return $this->assignments;
    }

    public function setAssignments($assignments): void
    {
        $this->assignments = $assignments;
    }

    public function getUsername()
    {
        return $this->getLogin();
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}