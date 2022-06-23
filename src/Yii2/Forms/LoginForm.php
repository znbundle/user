<?php

namespace ZnBundle\User\Yii2\Forms;

use yii\base\Model;
use ZnCore\Base\I18Next\Facades\I18Next;

class LoginForm extends Model
{

    const SCENARIO_SIMPLE = 'SCENARIO_SIMPLE';
	
    public $login;
    public $password;
    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'trim'],
            [['login', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => I18Next::t('user', 'auth.action.login'),
            'password' => I18Next::t('user', 'main.password'),
            'rememberMe' => I18Next::t('user', 'auth.remember_me'),
        ];
    }

    /*public function normalizeLogin($attribute)
    {
        $this->$attribute = mb_strtolower($this->$attribute);
        return;
        //$this->$attribute = LoginHelper::pregMatchLogin($this->$attribute);
        $isValid = \App::$domain->account->login->isValidLogin($this->$attribute);
        if($isValid) {
            $this->$attribute = \App::$domain->account->login->normalizeLogin($this->$attribute);
        } else {
            return;
        }
    }*/
}
