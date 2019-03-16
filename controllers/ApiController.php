<?php

namespace app\controllers;

use app\models\track\Track;
use app\models\track\UserPosition;
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

    public function actionTest()
    {
        return ['value' => 'Hello!', 'username' => Yii::$app->user->identity->email];
    }

    public function actionGetUserInfo()
    {
        $user = Yii::$app->user->identity;

        return [
            'fullname' => $user->first_name . $user->patronymic,
            'trackNumber' => $user->number_track
        ];
    }

    /**
     * Сохранение позиции маршруток
     */
    public function actionSendUserCoords()
    {
        $user = Yii::$app->user->identity;

        if (!Yii::$app->user->isGuest) {
            $model = new Track();

            $model->load(Yii::$app->request->post(), '');

            $model->user_id = $user->id;

            $model->save();
        }
    }

    /**
     * Получение транспорта поблизости
     */
    public function actionGetTransports()
    {
        $model = new UserPosition();

        $model->load(Yii::$app->request->post(), '');

        $transports = Track::find()->orderBy(['data' => SORT_DESC])->groupBy(['user_id'])->asArray()->all();

        return $transports;
    }

    /**
     * Проверка на авторизацию пользователя
     *
     * @return array
     */
    public function actionCheckAuth()
    {
        return ['status' => (Yii::$app->user->isGuest)];
    }
}
