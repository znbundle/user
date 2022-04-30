<?php

/* @var $this yii\web\View */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/restore-password/reset', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
