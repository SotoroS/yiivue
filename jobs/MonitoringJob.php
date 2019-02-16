<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 31.01.2019
 * Time: 19:47
 */

namespace app\jobs;

use app\models\Point;
use app\models\Serve;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

/**
 * Мониторинг текущих партий по теннису посредством API
 * выгрузка всей информации о партии в базу данных
 */
class MonitoringJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {
        try {
            // Получаем все текущие игровые события
            $inplayEvents = $this->GetInplayEvents();
            // Проходимся по всем текущим игровым событиям
            foreach ($inplayEvents as $event) {
                // Проверка на корректность полученных данных
                if (is_null($event->points)) continue;
                // Ищим последние добавленные очки по данному игровому событию
                $pointModel = Point::find()->where(['event_id' => $event->id])->orderBy(['id' => SORT_DESC])->one();
                // Если полученные очки не совподают с уже добавленными очками,
                // произвести добавление

//                echo $pointModel->value . ' : ' . $event->points . ' : ' . ($pointModel->value !== $event->points) . chr(0x0D) . chr(0x0A);

                if ($pointModel->value !== $event->points) {
                    // Если первый подающий еще неизвестен, производим вычисления
                    if (is_null($serveModel = Serve::findOne($event->id))) {
                        $eventInfo = $this->GetEventInfo($event->id);
                        // Определяем первого подающего
                        if (!is_null($eventInfo->events[0]->text)) {
                            $resultFirstGame = explode(' - ', $eventInfo->events[0]->text);
                            $playerName = $resultFirstGame[1];
                            $status = explode(' ', $resultFirstGame[2])[0];

                            $serveModel = new Serve();
                            $serveModel->id = $event->id;
                            $serveModel->name = ($status == 'holds') ? $playerName : ($event->home->name == $playerName) ? $event->away->name : $event->home->name;
                            $serveModel->save();
                        }


                    }
                    // Актуализируем данные о текущем колличестве очков
                    $pointModel = new Point();
                    $pointModel->event_id = $event->id;
                    $pointModel->value = $event->points;
                    $pointModel->save();
                }
            }
        } catch (\Exception $e) {
            echo $e;
        } finally {
            // Создаем новую задачу
            \Yii::$app->queue->push(new MonitoringJob());
        }

        //        // Проходимся по всем игровым событиям
//        foreach ($inplayEvents as $event) {
//            // Получаем дополнительную информацию о событии
//            $eventInfo = $this->GetEventInfo($event->id);
//
//            // Создаем игроков в базе данных
//
//            // Проверяем на существование игрока
//            if (is_null($firstPlayer = Player::findOne($event->home->id))) {
//                $firstPlayer = new Player();
//
//                // Заполняем модель первого игра
//                $firstPlayer->id = $event->home->id;
//                $firstPlayer->name = $event->home->name;
//            }
//
//            // Актуализируем статистику игрока
//            $firstPlayer->aces = $eventInfo->stats->aces[0];
//            $firstPlayer->double_faults = $eventInfo->stats->double_faults[0];
//            $firstPlayer->win_1st_serve = $eventInfo->stats->win_1st_serve[0];
//            $firstPlayer->break_point_conversions = $eventInfo->stats->break_point_conversions[0];
//
//            // Проверяем на существование игрока
//            if (is_null($secondPlayer = Player::findOne($event->away->id))) {
//                $secondPlayer = new Player();
//
//                // Заполняем модель второго игрока
//                $secondPlayer->id = $event->away->id;
//                $secondPlayer->name = $event->away->name;
//            }
//
//            // Актуализируем статистику игрока
//            $secondPlayer->aces = $eventInfo->stats->aces[1];
//            $secondPlayer->double_faults = $eventInfo->stats->double_faults[1];
//            $secondPlayer->win_1st_serve = $eventInfo->stats->win_1st_serve[1];
//            $secondPlayer->break_point_conversions = $eventInfo->stats->break_point_conversions[1];
//
//            // Определяем первого подающего
//            $resultFirstGame = explode(' - ', $eventInfo->events[0]->text);
//            $playerName = $resultFirstGame[1];
//            $status = explode(' ', $resultFirstGame[2])[0];
//
//            $firstServePlayer = ($status == 'holds') ? $playerName : ($firstPlayer->name == $playerName) ? $secondPlayer->name : $firstPlayer->name;
//
//            $sumScore = array_sum($this->multiexplode([',', '-'], $event->ss));
//
//            // Определяем подающего исходя из счета и резултатов первого гейма
//            if ($sumScore % 2 == 0) {
//                if ($firstPlayer->name == $firstServePlayer) {
//                    $firstPlayer->serve = 1;
//                    $secondPlayer->serve = 0;
//                } else {
//                    $firstPlayer->serve = 0;
//                    $secondPlayer->serve = 1;
//                }
//            } else {
//                if ($firstPlayer->name != $firstServePlayer) {
//                    $firstPlayer->serve = 1;
//                    $secondPlayer->serve = 0;
//                } else {
//                    $firstPlayer->serve = 0;
//                    $secondPlayer->serve = 1;
//                }
//            }
//
//            // Сохраняем модели игроков
//            $secondPlayer->save();
//            $firstPlayer->save();
//
//            // Проверка на существование игрового события в базе данных
//            if (is_null($eventModel = Event::findOne($event->id))) {
//                // Заполняем модель события и сохраняем ее
//                $eventModel = new Event();
//
//                $eventModel->id = $event->id;
//                $eventModel->league = $event->league->name;
//                $eventModel->first_player_id = $firstPlayer->id;
//                $eventModel->second_player_id = $secondPlayer->id;
//                $eventModel->score = $event->ss;
//
//                $eventModel->save();
//            }
//
//            break;
//        }
//

    }

    /**
     * Получение информации о текущих игровых событиях
     * @return object
     */
    private function GetInplayEvents()
    {
        // Генерируем URL для получения информаци о текущих играх
        $url = 'https://api.betsapi.com/v1/events/inplay?sport_id=' . \Yii::$app->params['sport_id'] . '&token=' . \Yii::$app->params['api_token'];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        // Если ничего не получили, завершаем итерацию
        if ($data === false) return;

        curl_close($ch);

        // Получем все текущие игры
        return json_decode($data)->results;
    }

    /**
     * Получение подробной информации о конуретном игровом событии
     * @param $id
     */
    private function GetEventInfo($id)
    {
        // Генерируем URL для получения информаци о текущих играх
        $url = 'https://api.betsapi.com/v1/event/view?token=' . \Yii::$app->params['api_token'] . '&event_id=' . $id;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        // Если ничего не получили, завершаем итерацию
        if ($data === false) return;

        curl_close($ch);

        // Получем все текущие игры
        return json_decode($data)->results[0];
    }

    /**
     * Допустимое время выполнение
     * @return float|int
     */
    public function getTtr()
    {
        return 600;
    }

    /**
     * Разврешение на повторное выполнение в слуае неудачи
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return false;
    }

    /**
     * @param $delimiters
     * @param $string
     * @return array
     */
    private function multiexplode($delimiters, $string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }
}