<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Player */
?>
<div class="player-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'aces',
            'double_faults',
            'win_1st_serve',
            'break_point_conversions',
            'serve',
        ],
    ]) ?>

</div>
