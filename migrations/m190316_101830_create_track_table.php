<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%track}}`.
 */
class m190316_101830_create_track_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%track}}', [
            'id' => $this->primaryKey(),
            'lat' => $this->double(),
            'lng' => $this->double(),
            'time' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk_track_user_id',
            'track',
            'user_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%track}}');
    }
}
