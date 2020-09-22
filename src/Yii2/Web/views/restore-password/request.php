<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\PasswordResetRequestForm */

use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = I18Next::t('account', 'restore-password.title');
//\App::$domain->navigation->breadcrumbs->create($this->title);
?>
<div class="user-request-password-reset">
	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= I18Next::t('account', 'restore-password.request_text') ?></p>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(); ?>

				<?= $form->field($model, 'login')->textInput(['autofocus' => true]) ?>

				<div class="form-group">
					<?= Html::submitButton(Yii::t('action', 'next'), ['class' => 'btn btn-primary']) ?>
				</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
