<?php
/**
 * @var $this yii\web\View
 * @var $model yii\base\Model
 */

use ZnLib\Components\I18Next\Facades\I18Next;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = I18Next::t('user', 'security.email');
//\App::$domain->navigation->breadcrumbs->create($this->title);

?>

<?= Tabs::widget([
	'items' => MenuHelper::gen('ZnBundle\User\Yii2\Web\helpers\SecurityMenu'),
]) ?>

<br/>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'email')->textInput(['placeholder'=>$model->email]) ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('action', 'update'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
