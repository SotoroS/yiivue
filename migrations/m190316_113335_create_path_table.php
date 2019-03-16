<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%path}}`.
 */
class m190316_113335_create_path_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%path}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%path}}');
    }
}
