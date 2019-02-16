<?php

use yii\db\Migration;

/**
 * Class m190206_163540_create_foreign_key
 */
class m190206_163540_create_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $this->addForeignKey(
//            'fk_event_first_player',
//            'event',
//            'first_player_id',
//            'player',
//            'id'
//        );
//
//        $this->addForeignKey(
//            'fk_event_second_player',
//            'event',
//            'second_player_id',
//            'player',
//            'id'
//        );
//
//        $this->addForeignKey(
//            'fk_point_event',
//            'point',
//            'event_id',
//            'event',
//            'id'
//        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_event-first_player');
        $this->dropForeignKey('fk_event-second_player');
        $this->dropForeignKey('fk_point-event');
    }
}
