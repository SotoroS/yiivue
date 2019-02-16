<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 06.02.2019
 * Time: 10:04
 */

/* @var $this yii\web\View */

use yii\widgets\Pjax;

$this->title = $viewGame->home->name . ' - ' . $viewGame->away->name;

?>

<div class="site-view">

    <?php Pjax::begin(['id' => 'monitoring-game']) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            История геймов матча <?= $viewGame->home->name . ' - ' . $viewGame->away->name ?>
            <p class="pull-right">Время составления - <?= $time ?></p>
        </div>

        <?php
            // Определяем первого подающего
            $resultFirstGame = explode(' - ', $viewGame->events[0]->text);
            $player = $resultFirstGame[1];
            $status = explode(' ', $resultFirstGame[2])[0];

            $submitPlayer = ($status == 'holds') ? $player : ($viewGame->home->name == $player) ? $viewGame->away->name : $viewGame->home->name;
        ?>

        <div class="panel-body">
            Первый подающий - <?= $submitPlayer ?>
        </div>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>№</th>
                <th>Подающий</th>
                <th>Результат</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($viewGame->events as $event) { ?>

            <?php $eventInfo = explode(' - ', $event->text); ?>

                <tr>
                    <td><?= $eventInfo[0] ?></td>
                    <td><?= $eventInfo[1] ?></td>
                    <td><?= $eventInfo[2] ?></td>
                </tr>

            <?php } ?>

            </tbody>
        </table>
    </div>

    <?php Pjax::end() ?>

    <pre>
        <?= print_r($viewGame) ?>
    </pre>

</div>
