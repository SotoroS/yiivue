<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 16.03.2019
 * Time: 14:58
 */

namespace app\models\track;

use yii\base\Model;

class UserPosition extends Model
{
    public $leftUpLat;
    public $leftUpLng;
    public $rightDownLat;
    public $rightDownLng;
    public $filter;
}