<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-signup">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <p class="text-center">Для регистрации заполните следующие поля:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'errorCssClass' => 'has-danger',
                'errorSummaryCssClass' => 'alert alert-danger',
            ]); ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'firstName') ?>
            <?= $form->field($model, 'patronymic') ?>
            <?= $form->field($model, 'secondName') ?>
            <?= $form->field($model, 'numberTrack') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <br>

            <div class="form-group">
                <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary full-btn', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>