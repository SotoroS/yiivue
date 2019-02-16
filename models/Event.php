<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $league
 * @property int $first_player_id
 * @property int $second_player_id
 * @property string $score
 *
 * @property Player $firstPlayer
 * @property Player $secondPlayer
 * @property Point[] $points
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_player_id', 'second_player_id'], 'integer'],
            [['league', 'score'], 'string', 'max' => 255],
            [['first_player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Player::className(), 'targetAttribute' => ['first_player_id' => 'id']],
            [['second_player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Player::className(), 'targetAttribute' => ['second_player_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'league' => 'League',
            'first_player_id' => 'First Player ID',
            'second_player_id' => 'Second Player ID',
            'score' => 'Score',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'first_player_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecondPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'second_player_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Point::className(), ['event_id' => 'id']);
    }
}
