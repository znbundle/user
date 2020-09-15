<?php


namespace ZnBundle\User\Yii\Entities;

use yii\web\IdentityInterface;
use ZnCore\Base\Legacy\Traits\MagicAttribute\MagicAttributeTrait;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Helpers\EntityHelper;

class IdentityEntity extends \ZnBundle\User\Domain\Entities\IdentityEntity implements IdentityInterface
{

    use MagicAttributeTrait;

    public $created_at = null;
    private $assignments;

    public function getRoles(): array {
        /*if(isset($this->roles)) {
            return $this->roles;
        }*/
        if(!isset($this->assignments)) {
            return null;
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

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}