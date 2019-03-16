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
                    'send-user-coords' => ['post'],
                    'get-transports' => ['post'],
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

        if (is_empty($model->filter)) {
            $transports = Track::find()->orderBy(['data' => SORT_DESC])->groupBy(['user_id'])->asArray()->all();
        }

//        return $transports;
    return '{
    "response": {
      "metaInfo": {
        "timestamp": "2019-03-16T12:09:55Z",
        "mapVersion": "8.30.94.152",
        "moduleVersion": "7.2.201910-2424",
        "interfaceVersion": "2.6.53",
        "availableMapVersion": [
          "8.30.94.152"
        ]
      },
      "route": [
        {
          "waypoint": [
            {
              "linkId": "+1230749651",
              "mappedPosition": {
                "latitude": 48.5700095,
                "longitude": 44.4361353
              },
              "originalPosition": {
                "latitude": 48.5707,
                "longitude": 44.4366
              },
              "type": "stopOver",
              "spot": 0,
              "sideOfStreet": "right",
              "mappedRoadName": "",
              "label": "",
              "shapeIndex": 0
            },
            {
              "linkId": "+1142535090",
              "mappedPosition": {
                "latitude": 48.7856792,
                "longitude": 44.5618708
              },
              "originalPosition": {
                "latitude": 48.7857,
                "longitude": 44.5619
              },
              "type": "stopOver",
              "spot": 0.1,
              "sideOfStreet": "right",
              "mappedRoadName": "улица Германа Титова",
              "label": "улица Германа Титова",
              "shapeIndex": 256
            }
          ],
          "mode": {
            "type": "fastest",
            "transportModes": [
              "publicTransport"
            ],
            "trafficMode": "disabled",
            "feature": []
          },
          "leg": [
            {
              "start": {
                "linkId": "+1230749651",
                "mappedPosition": {
                  "latitude": 48.5700095,
                  "longitude": 44.4361353
                },
                "originalPosition": {
                  "latitude": 48.5707,
                  "longitude": 44.4366
                },
                "type": "stopOver",
                "spot": 0,
                "sideOfStreet": "right",
                "mappedRoadName": "",
                "label": "",
                "shapeIndex": 0
              },
              "end": {
                "linkId": "+1142535090",
                "mappedPosition": {
                  "latitude": 48.7856792,
                  "longitude": 44.5618708
                },
                "originalPosition": {
                  "latitude": 48.7857,
                  "longitude": 44.5619
                },
                "type": "stopOver",
                "spot": 0.1,
                "sideOfStreet": "right",
                "mappedRoadName": "улица Германа Титова",
                "label": "улица Германа Титова",
                "shapeIndex": 256
              },
              "length": 29990,
              "travelTime": 6654,
              "maneuver": [
                {
                  "position": {
                    "latitude": 48.5700095,
                    "longitude": 44.4361353
                  },
                  "instruction": "Head <span class=\"heading\">west</span>. <span class=\"distance-description\">Go for <span class=\"length\">225 m</span>.</span>",
                  "travelTime": 234,
                  "length": 225,
                  "id": "M1",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.5706317,
                    "longitude": 44.4332278
                  },
                  "instruction": "Turn <span class=\"direction\">left</span> onto <span class=\"next-street\">посёлок Саши Чекалина</span>. <span class=\"distance-description\">Go for <span class=\"length\">235 m</span>.</span>",
                  "travelTime": 235,
                  "length": 235,
                  "id": "M2",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.5687542,
                    "longitude": 44.431994
                  },
                  "instruction": "Go to the stop <span class=\"station\">Химгородок</span> and take the <span class=\"transit\">bus</span> <span class=\"line\">49</span> toward <span class=\"destination\">Посёлок Руднева</span>. <span class=\"distance-description\">Follow for <span class=\"stops\">10 stops</span>.</span>",
                  "travelTime": 1395,
                  "length": 6120,
                  "id": "M3",
                  "stopName": "Химгородок",
                  "_type": "PublicTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.6136115,
                    "longitude": 44.4227672
                  },
                  "instruction": "Get off at <span class=\"station\">Кинотеатр Авангард</span> and change to the <span class=\"transit\">bus</span> <span class=\"line\">40</span> toward <span class=\"destination\">Колхозный рынок</span>. <span class=\"distance-description\">Follow for <span class=\"stops\">36 stops</span>.</span>",
                  "travelTime": 4405,
                  "length": 23061,
                  "id": "M4",
                  "stopName": "Кинотеатр Авангард",
                  "_type": "PublicTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7846935,
                    "longitude": 44.565289
                  },
                  "instruction": "Get off at <span class=\"station\">Улица Титова</span>.",
                  "travelTime": 0,
                  "length": 0,
                  "id": "M5",
                  "stopName": "Улица Титова",
                  "_type": "PublicTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7846935,
                    "longitude": 44.565289
                  },
                  "instruction": "Head <span class=\"heading\">southwest</span>. <span class=\"distance-description\">Go for <span class=\"length\">11 m</span>.</span>",
                  "travelTime": 21,
                  "length": 11,
                  "id": "M6",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7846935,
                    "longitude": 44.5651817
                  },
                  "instruction": "Turn <span class=\"direction\">left</span> onto <span class=\"next-street\">улица Маршала Ерёменко</span>. <span class=\"distance-description\">Go for <span class=\"length\">13 m</span>.</span>",
                  "travelTime": 25,
                  "length": 13,
                  "id": "M7",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7845969,
                    "longitude": 44.5650637
                  },
                  "instruction": "Turn <span class=\"direction\">right</span>. <span class=\"distance-description\">Go for <span class=\"length\">32 m</span>.</span>",
                  "travelTime": 36,
                  "length": 32,
                  "id": "M8",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7847149,
                    "longitude": 44.5647097
                  },
                  "instruction": "Turn <span class=\"direction\">left</span> onto <span class=\"next-street\">улица Маршала Ерёменко</span>. <span class=\"distance-description\">Go for <span class=\"length\">67 m</span>.</span>",
                  "travelTime": 77,
                  "length": 67,
                  "id": "M9",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7842751,
                    "longitude": 44.5640874
                  },
                  "instruction": "Turn <span class=\"direction\">right</span> onto <span class=\"next-street\">улица Германа Титова</span>. <span class=\"distance-description\">Go for <span class=\"length\">226 m</span>.</span>",
                  "travelTime": 226,
                  "length": 226,
                  "id": "M10",
                  "_type": "PrivateTransportManeuverType"
                },
                {
                  "position": {
                    "latitude": 48.7856792,
                    "longitude": 44.5618708
                  },
                  "instruction": "Arrive at <span class=\"street\">улица Германа Титова</span>. Your destination is on the right.",
                  "travelTime": 0,
                  "length": 0,
                  "id": "M11",
                  "_type": "PrivateTransportManeuverType"
                }
              ]
            }
          ],
          "publicTransportLine": [
            {
              "lineName": "49",
              "companyName": "",
              "destination": "Посёлок Руднева",
              "type": "busPublic",
              "id": "L1"
            },
            {
              "lineName": "40",
              "companyName": "",
              "destination": "Колхозный рынок",
              "type": "busPublic",
              "id": "L2"
            }
          ],
          "summary": {
            "distance": 29990,
            "baseTime": 6654,
            "flags": [
              "noThroughRoad",
              "builtUpArea"
            ],
            "text": "The trip takes <span class=\"length\">30.0 km</span> and <span class=\"time\">1:51 h</span>.",
            "travelTime": 6654,
            "departure": "1970-01-01T00:00:00Z",
            "_type": "PublicTransportRouteSummaryType"
          }
        }
      ],
      "language": "en-us"
    }
  }';
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
