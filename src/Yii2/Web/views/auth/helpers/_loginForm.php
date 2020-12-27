<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \ZnBundle\User\Yii2\Web\forms\LoginForm */

use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<br/>

<p class="login-box-msg"><?= I18Next::t('user', 'auth.login_text') ?></p>

<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'login') ?>

	<?= $form->field($model, 'password')->passwordInput() ?>
	
	<?=$form->field($model, 'rememberMe', [
		'checkboxTemplate'=>'<div class="checkbox">{beginLabel}{input}{labelTitle}{endLabel}{error}{hint}</div>',
	])->checkbox();?>
	
	<div class="form-group">
		<?=Html::submitButton(I18Next::t('user', 'auth.login_action'), ['class' => 'btn btn-primary btn-flat', 'name' => 'login-button']) ?>
	</div>
	
<?php ActiveForm::end(); ?>

<?= Html::a(I18Next::t('user', 'auth.register_new_user'), ['/user/registration']) ?>
    <br/>
<?= Html::a(I18Next::t('user', 'auth.i_forgot_my_password'), ['/user/restore-password']) ?>