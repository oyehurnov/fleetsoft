<?php

use yii\db\Migration;

class m251030_115303_seed_user_and_book_data extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'author' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'published_at' => $this->date(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%book}}');
        $this->dropTable('{{%user}}');
    }
}
