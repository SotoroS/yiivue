<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 14:38
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DriverController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage and all vue.js pages
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
