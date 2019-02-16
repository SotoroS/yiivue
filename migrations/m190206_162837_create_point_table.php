<?php

use yii\db\Migration;

/**
 * Handles the creation of table `point`.
 */
class m190206_162837_create_point_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $this->createTable('point', [
//            'id' => $this->primaryKey(),
//            'event_id' => $this->integer(),
//            'first_player' => $this->string(),
//            'second_player' => $this->string(),
//        ]);

        $this->createTable('point', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'value' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('point');
    }
}
