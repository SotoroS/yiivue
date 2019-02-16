<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
?>
<div class="event-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'league',
            'first_player_id',
            'second_player_id',
            'score',
        ],
    ]) ?>

</div>
