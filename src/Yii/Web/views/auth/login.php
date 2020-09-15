<?php

/* @var $this yii\web\View */
/* @var $model LoginForm */

use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use yii\helpers\Html;
use yii2rails\app\domain\helpers\EnvService;
use ZnBundle\User\Yii\Forms\LoginForm;

$this->title = I18Next::t('account', 'auth.login_title');
////\App::$domain->navigation->breadcrumbs->create($this->title);

$loginForm = $this->render('helpers/_loginForm.php', [
	'model' => $model,
]);

$items = [];
$items[] = [
	'label' => I18Next::t('account', 'auth.title'),
	'content' => $loginForm,
];

/*if(\App::$domain->account->oauth->isEnabled()) {
	$items[] = [
		'label' => I18Next::t('account', 'oauth.title'),
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

<?php if(APP == BACKEND) { ?>

	<div class="login-box">
		<div class="login-logo">
			<?= Html::encode($this->title) ?>
		</div>
		<div class="login-box-body">
			<?= $loginForm ?>
			<?= Html::a(Yii::t('main', 'go_to_frontend'), $_ENV['WEB_URL']) ?>
		</div>
	</div>

<?php } else { ?>

	<div class="user-login">
		<h1>
			<?= Html::encode($this->title) ?>
		</h1>
		<div class="row">
			<div class="col-lg-5">
                <?= $html ?>
			</div>
		</div>
	</div>

<?php } ?>
