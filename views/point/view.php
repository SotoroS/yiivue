<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Point */
?>
<div class="point-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'event_id',
            'first_player',
            'second_player',
        ],
    ]) ?>

</div>
