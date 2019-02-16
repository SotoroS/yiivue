<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Queue */
?>
<div class="queue-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'channel',
            'job',
            'pushed_at',
            'ttr',
            'delay',
            'priority',
            'reserved_at',
            'attempt',
            'done_at',
        ],
    ]) ?>

</div>
