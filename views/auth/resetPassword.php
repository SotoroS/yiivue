<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 12:41
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Восстаноление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-reset-password">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
            <p class="text-center">Пожалуйста, введите новый пароль:</p>

            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                'errorCssClass' => 'has-danger',
                'errorSummaryCssClass' => 'alert alert-danger',
            ]); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary full-btn']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>