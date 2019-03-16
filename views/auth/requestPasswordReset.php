<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 12:34
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Восставноление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-request-password-reset">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <p class="text-center">Пожалуйста, заполните следующие поля.</p>

            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'errorCssClass' => 'has-danger',
                'errorSummaryCssClass' => 'alert alert-danger',
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Восставновить', ['class' => 'btn btn-primary full-btn']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>