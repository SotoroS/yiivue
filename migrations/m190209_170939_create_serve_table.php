<?php

use yii\db\Migration;

/**
 * Handles the creation of table `serve`.
 */
class m190209_170939_create_serve_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('serve', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('serve');
    }
}
