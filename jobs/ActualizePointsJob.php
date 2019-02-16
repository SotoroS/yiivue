<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 07.02.2019
 * Time: 19:28
 */

namespace app\jobs;

use app\models\Event;
use app\models\Point;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

/**
 * Актуализация данных о очках в текущих матчей
 */
class ActualizePointsJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {
        try {
            // Получаем все текущие игровые события
            $inplayEvents = $this->GetInplayEvents();

            // Проходимся по всем игровым событиям
            foreach ($inplayEvents as $event) {
                // Получаем модель события
                $eventModel = Event::findOne($event->id);

                if (!is_null($eventModel)) {
                    // Последнее значение очков
                    $lastPoint = $eventModel->getPoints()->orderBy(['id' => SORT_DESC])->one();

                    // Разбиваем строку с очками
                    $points = explode('-', $event->points);

                    if ($lastPoint->first_player != $points[0] && $lastPoint->second_player != $points[1]) {
                        // Заполняем модель баллов и сохраняем ее
                        $pointModel = new Point();

                        $pointModel->event_id = $eventModel->id;
                        $pointModel->first_player = $points[0];
                        $pointModel->second_player = $points[1];
                        $pointModel->save();
                    }
                }
                break;
            }
        } catch (\Exception $e) {
            echo $e;
        } finally {
            // Создаем новую задачу
            \Yii::$app->pointQueue->push(new ActualizePointsJob());
        }
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
     * Допустимое время выполнение
     * @return float|int
     */
    public function getTtr()
    {
        return 60;
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
}
