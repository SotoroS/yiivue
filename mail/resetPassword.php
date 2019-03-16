<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 12:22
 */

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset-password', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->fullname) ?>,</p>
    <p>Для восстановления парля перейдите по следующе ссылке:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>