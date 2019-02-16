<?php

/* @var $this yii\web\View */

use app\models\Queue;
use app\models\Serve;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Мониторинг';

?>

<div class="site-index">

    <?php Pjax::begin(['id' => 'monitoring-table']) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            Мониторинг by <a href="https://vk.com/fokin_danil" target="_blank">sotoros</a>
            <p class="pull-right">
                Время составления - <?= $time ?> &#9;
                <?php
                if (Queue::find()->count() == 0) {
                    echo Html::a('<i class="glyphicon glyphicon-play") ></i>', ['start'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-control', 'title' => 'Запуск']);
                } else {
                    echo Html::a('<i class="glyphicon glyphicon-stop") ></i>', ['stop'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-control', 'title' => 'Стоп']);
                }
                ?>
            </p>
        </div>

        <table class="table table-condensed table-bordered">
            <thead>
            <tr>
                <th width="20%">Лига</th>
                <th class="text-center" width="10%">Счет</th>
                <th>Подающий | Игроки | Очки</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($inplayGame as $game) { ?>
                <?php
                $firstServePlayerName = Serve::findOne(['id' => $game->id])->name;
                $secondServePlayerName = ($game->home->name == $firstServePlayerName) ? $game->away->name : $game->home->name;
                $indexGame = 0;
                ?>
                <tr>
                    <td><?= $game->league->name . ' . ' . $firstServePlayerName ?></td>
                    <td class="text-center"><?= $game->ss ?></td>
                    <td>
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse-<?= $game->id ?>">
                                            <?php
                                            $content = null;
                                            foreach (\app\models\Point::findAll(['event_id' => $game->id]) as $key => $pointModel) {

                                                $points = explode('-', $pointModel->value);
                                                $servePlayerName = ($indexGame % 2 == 0) ? $firstServePlayerName : $secondServePlayerName;

                                                if (($points[0] == 0 && $points[1] == 0) || $key == 0) {
                                                    $content .= ($key != 0) ? '<hr>' : '';

                                                    if ($key != 0) break;

                                                    $content .= '<div class="col player-col"><div class="player-box"><div class="ball-box">';
                                                    $content .= ($servePlayerName == $game->home->name) ? '<span class="ball"></span>' : '';
                                                    $content .= '</div>';
                                                    $content .= $game->home->name;
                                                    $content .= '<br><div class="ball-box">';
                                                    $content .= ($servePlayerName == $game->away->name) ? '<span class="ball"></span>' : '';
                                                    $content .= '</div>';
                                                    $content .= $game->away->name;
                                                    $content .= '</div></div>';

                                                    $indexGame++;
                                                }
                                                $content .= '<div class="col points-col"><div class="point-box">' . $points[0] . '</div>';
                                                $content .= '<div class="point-box">' . $points[1] . '</div></div>';
                                            } ?>
                                            <?= $content ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-<?= $game->id ?>" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?php foreach (\app\models\Point::findAll(['event_id' => $game->id]) as $key => $pointModel) { ?>
                                            <?php
                                            $points = explode(' - ', $pointModel->value);
                                            $servePlayerName = ($indexGame % 2 == 0) ? $firstServePlayerName : $secondServePlayerName;
                                            ?>
                                            <?php if (($points[0] == 0 && $points[1] == 0) || $key == 0) { ?>
                                                <?= ($key != 0) ? '<hr>' : '' ?>
                                                <div class="col player-col">
                                                    <div class="player-box">
                                                        <div class="ball-box">
                                                            <?= ($servePlayerName == $game->home->name) ? '<span class="ball"></span>' : '' ?>
                                                        </div>
                                                        <?= $game->home->name ?>
                                                        <br>
                                                        <div class="ball-box">
                                                            <?= ($servePlayerName == $game->away->name) ? '<span class="ball"></span>' : '' ?>
                                                        </div>
                                                        <?= $game->away->name ?>
                                                    </div>
                                                </div>
                                                <?php $indexGame++ ?>
                                            <?php } ?>
                                            <div class="col points-col">
                                                <div class="point-box">
                                                    <?= $points[0] ?>
                                                </div>
                                                <div class="point-box">
                                                    <?= $points[1] ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php Pjax::end() ?>
</div>
