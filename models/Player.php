<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "player".
 *
 * @property int $id
 * @property string $name
 * @property int $aces
 * @property int $double_faults
 * @property int $win_1st_serve
 * @property int $break_point_conversions
 * @property int $serve
 *
 * @property Event[] $events
 * @property Event[] $events0
 */
class Player extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aces', 'double_faults', 'win_1st_serve', 'break_point_conversions', 'serve'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'aces' => 'Aces',
            'double_faults' => 'Double Faults',
            'win_1st_serve' => 'Win 1st Serve',
            'break_point_conversions' => 'Break Point Conversions',
            'serve' => 'Serve',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventsByFirstPlayer()
    {
        return $this->hasMany(Event::className(), ['first_player_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventsBySecondPlayer()
    {
        return $this->hasMany(Event::className(), ['second_player_id' => 'id']);
    }
}
