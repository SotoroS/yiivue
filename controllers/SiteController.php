<?php

namespace app\controllers;

use app\jobs\MonitoringJob;
use app\models\Point;
use app\models\Queue;
use app\models\Serve;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Отображаем информацию о текущих игровых событиях
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
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

        $result = json_decode($data)->results;

        // Дополняем информацию об игровом событии
        for ($i = 0; $i < count($result); $i++) {
            // Имя первого подающего
            $firstServePlayerName = Serve::findOne(['id' => $result[$i]->id])->name;
            // Имя второго подяющего
            $secondServePlayerName = ($firstServePlayerName == $result[$i]->home->name) ? $result[$i]->away->name : $result[$i]->home->name;

            // Все очки игрового события
            $allPoints = Point::find()->where(['event_id' => $result[$i]->id])->select('value')->asArray()->all();

            $indexGame = 0;
            $indexPointInGame = 0;

            // Формируме массив геймов
            for ($j = 0; $j < count($allPoints); $j++) {
                if ($allPoints[$j]['value'] == '0-0' && $j != 0) {
                    $indexPointInGame = 0;
                    $indexGame++;
                }

                // Добавляем очки в гейм
                $result[$i]->games[$indexGame]->points[$indexPointInGame] = explode('-', $allPoints[$j]['value']);
                $indexPointInGame++;
            }

            // Переворачиваем массив геймов
            if (count($result[$i]->games) > 0) {
                $result[$i]->games = array_reverse($result[$i]->games);
            }

            // Получаем сумму счета
            foreach ($result[$i]->scores as $score) {
                $result[$i]->scores->sum += $score->home + $score->away;
            }

            // Добавляем информацию о подающего
            for ($j = $result[$i]->scores->sum, $index = 0; $j > $result[$i]->scores->sum - count($result[$i]->games); $j--) {
                $servePlayerName = ($j % 2 == 0) ? $firstServePlayerName : $secondServePlayerName;
                $result[$i]->games[$index++]->serve = $servePlayerName;
            }
        }

        // Получем все текущие игры
        return $result;
    }

    /**
     * @return mixed|void
     */
    public function actionTest()
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
        return $data;
    }

    public function actionJobTest() {
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
            if ($pointModel->value !== $event->points) {
                // Если первый подающий еще неизвестен, производим вычисления
//                if (is_null($serveModel = Serve::findOne($event->id))) {

                    // Получаем дополнительную информацию
                    $eventInfo = $this->GetEventInfo($event->id);

                    // Определяем первого подающего
                    if (!is_null($eventInfo->events[0]->text)) {
                        $resultFirstGame = explode(' - ', $eventInfo->events[0]->text);
                        $playerName = $resultFirstGame[1];
                        $status = explode(' ', $resultFirstGame[2])[0];

                        $serveModel = new Serve();
                        $serveModel->id = $event->id;
                        $serveModel->name = ($status == 'holds') ? $playerName : (($event->home->name == $playerName) ? $event->away->name : $event->home->name);
                        echo '<pre>' . $eventInfo->events[0]->text . ' Serve: ' . $serveModel->name . '<br>'
                            . ($status == 'holds')
                            . '</pre>';
                        //                        $serveModel->save();
                    }
//                }

                // Актуализируем данные о текущем колличестве очков
                $pointModel = new Point();
                $pointModel->event_id = $event->id;
                $pointModel->value = $event->points;
//                $pointModel->save();
            }
        }
    }

    /**
     * Start queue
     * @return array|Response
     */
    public function actionStart()
    {
        $request = Yii::$app->request;
        Yii::$app->queue->push(new MonitoringJob());

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#monitoring-table'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Stop queue
     * @return array|Response
     */
    public function actionStop()
    {
        $request = Yii::$app->request;
        Queue::deleteAll();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#monitoring-table'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
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
}
