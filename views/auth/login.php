<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <p class="text-center">Пожалуйста, заполните следующие поля:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'errorCssClass' => 'has-danger',
                'errorSummaryCssClass' => 'alert alert-danger',
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary full-btn', 'name' => 'login-button']) ?>
            </div>

            <div class="text-muted">
                Если вы забыли пароль, вы можете <?= Html::a('восставноить его', ['/auth/request-password-reset']) ?>.
            </div>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>