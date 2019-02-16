<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'league',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'firstPlayer.name',
        'value' => function ($model) {
            $text = $model->firstPlayer->name;

            if ($model->firstPlayer->serve) {
                $text = '<b>' . $text . '</b>';
            }

            return $text;
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'secondPlayer.name',
        'value' => function ($model) {
            $text = $model->secondPlayer->name;

            if ($model->secondPlayer->serve) {
                $text = '<b>' . $text . '</b>';
            }

            return $text;
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'score',
        'value' => function ($model) {
            $firstPlayerPoint = '';
            $secondPlayerPoint = '';

            foreach ($model->points as $point)
            {
                $firstPlayerPoint .= ' ' . $point->first_player;
                $secondPlayerPoint .= ' ' . $point->second_player;
            }

            return $firstPlayerPoint . '<br>' . $secondPlayerPoint;
        },
        'format' => 'raw',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
    ],

];   