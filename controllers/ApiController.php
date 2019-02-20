<?php

namespace app\controllers;

use app\jobs\MonitoringJob;
use app\models\LoginForm;
use app\models\Point;
use app\models\Queue;
use app\models\Serve;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $result['token'] = \Yii::$app->request->csrfToken;
        return $result;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->post(), '');
        if ($model->login()) {
            return ['result' => 'success', 'user_id' => Yii::$app->user->getId()];
        } else {
            return ['result' => 'error', 'messages' => $model->getFirstErrors()];
        }
    }

    /**
     * Отображаем информацию о текущих игровых событиях
     *
     * @return string
     */
    public function actionEvent()
    {
        $request = Yii::$app->request;

        $events = $this->GetInplayEvents($request->get('count'));

        return ['result' => 'success', 'events' => $events, 'date' => date('H:i:s Y-m-d')];
    }

    /**
     * Получение информации о текущих игровых событиях
     * @return object
     */
    private function GetInplayEvents($countRepeat = 2)
    {
        // Генерируем URL для получения информаци о текущих играх
        $url = 'https://api.betsapi.com/v1/events/inplay?sport_id='
            . \Yii::$app->params['sport_id']
            . '&token='
            . \Yii::$app->params['api_token'];

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

            $count = 0;

            // Подсвесиваем повторение
            for ($j = (($result[$i]->home->name == $firstServePlayerName) ? 3 : 2);
                 $j < $indexGame + 1;
                 $j += 2) {
                // Отключаем изначально у всех подстветку
                $result[$i]->games[$j]->select = false;

                // Поиск последовательности
                if (($result[$i]->home->name == $firstServePlayerName
                        && ($result[$i]->games[$j]->points[1][0] == $result[$i]->games[$j - 2]->points[1][0])
                        && ($result[$i]->games[$j]->points[1][0] == 15))
                    || ($result[$i]->home->name == $firstServePlayerName
                        && ($result[$i]->games[$j]->points[1][1] == $result[$i]->games[$j - 2]->points[1][1])
                        && ($result[$i]->games[$j]->points[1][1] == 15))) {
                    $count++;
                } else {
                    $count = 0;
                }

                // Выделяем последовательность
                if ($count >= $countRepeat) {
                    for ($k = $j; $k >= $j - ($countRepeat * 2); $k -= 2) {
                        $result[$i]->games[$k]->select = true;
                    }
                }
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
            for ($j = $result[$i]->scores->sum, $index = 0; $index < count($result[$i]->games); $j--) {
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