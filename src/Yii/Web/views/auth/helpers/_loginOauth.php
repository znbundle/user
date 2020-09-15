<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use yii\authclient\widgets\AuthChoice;

?>

<br/>

<p class="login-box-msg"><?= I18Next::t('account', 'oauth.login_text') ?></p>

<?= AuthChoice::widget([
	'baseAuthUrl' => ['/user/oauth/login'],
]) ?>
