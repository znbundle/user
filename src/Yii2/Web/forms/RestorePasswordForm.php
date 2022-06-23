<?php

namespace ZnBundle\User\Yii2\Web\forms;

use ZnCore\Base\I18Next\Facades\I18Next;
use Yii;
use yii\helpers\ArrayHelper;

class RestorePasswordForm extends ApiRestorePasswordForm {
	
	
	public $new_password_repeat;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [
			[['new_password_repeat'], 'trim'],
			[['new_password_repeat'], 'required'],
			[['new_password_repeat'], 'string', 'min' => 8],
			['new_password_repeat', 'compare', 'compareAttribute' => 'password',],
		]);
	}
	
	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'new_password_repeat' => I18Next::t('user', 'security.new_password_repeat'),
		]);
	}
	
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_CONFIRM][] = 'new_password_repeat';
		return $scenarios;
	}
	
}
