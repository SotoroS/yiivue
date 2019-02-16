<?php

use yii\db\Migration;

/**
 * Handles the creation of table `event`.
 */
class m190131_191955_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('event', [
            'id' => $this->primaryKey(),
            'league' => $this->string(),
            'first_player_id' => $this->integer(),
            'second_player_id' => $this->integer(),
            'score' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('event');
    }
}
