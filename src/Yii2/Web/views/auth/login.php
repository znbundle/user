<?php

/* @var $this yii\web\View */
/* @var $model LoginForm */

use ZnLib\Components\I18Next\Facades\I18Next;
use yii\helpers\Html;
use ZnBundle\User\Yii2\Forms\LoginForm;

$this->title = I18Next::t('authentication', 'auth.login_title');

$loginForm = $this->render('helpers/_loginForm.php', [
	'model' => $model,
]);

$items = [];
$items[] = [
	'label' => I18Next::t('authentication', 'auth.title'),
	'content' => $loginForm,
];

/*if(\App::$domain->account->oauth->isEnabled()) {
	$items[] = [
		'label' => I18Next::t('user', 'oauth.title'),
		'content' => $this->render('helpers/_loginOauth.php'),
	];
}*/

if(count($items) > 1) {
    $html = \yii\bootstrap\Tabs::widget([
	    'items' => $items,
    ]);
} else {
	$html = $loginForm;
}

?>

<div class="user-login">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <?= $html ?>
</div>
