<?php

use yii\db\Migration;

/**
 * Handles the creation of table `player`.
 */
class m190206_162821_create_player_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('player', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'aces' => $this->integer(),
            'double_faults' => $this->integer(),
            'win_1st_serve' => $this->integer(),
            'break_point_conversions' => $this->integer(),
            'serve' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('player');
    }
}
