<?php

namespace ZnBundle\User\Yii\Interfaces\Entities;

use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use ZnBundle\User\Yii\Entities\LoginEntity;

/**
 * Interface LoginEntityInterface
 *
 * @package yii2bundle\account\domain\v3\interfaces\entities
 *
 * @property integer          $id
 * @property string           $login
 * @property integer          $status
 * @property string           $token
 * @property array            $roles
 * @property string           $username
 * @property string           $created_at
 * @property SecurityEntity   $security
 */
interface LoginEntityInterface extends IdentityInterface {
	


}