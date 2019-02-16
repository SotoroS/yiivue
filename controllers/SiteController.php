<?php

namespace app\controllers;

use Yii;
use app\models\Queue;
use app\jobs\MonitoringJob;
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
        $inplayGame = $this->GetInplayEvents();

        return $this->render('index', [
            'inplayGame' => $inplayGame,
            'time' => date('H:i:s Y-m-d'),
        ]);
    }

    /**
     * Отображаем подробную информацил о игровом событии.
     *
     * @return string
     */
    public function actionView($id)
    {
        $viewGame = $this->GetEventInfo($id);

        return $this->render('view', [
            'viewGame' => $viewGame,
            'time' => date('H:i:s Y-m-d'),
        ]);
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
}
