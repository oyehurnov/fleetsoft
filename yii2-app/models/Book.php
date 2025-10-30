<?php
namespace app\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public static function tableName() { return '{{%book}}'; }

    public function rules() {
        return [
            [['title', 'author'], 'required'],
            [['title', 'author'], 'string', 'max' => 255],
            ['description', 'string'],
            ['published_at', 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function fields() {
        return [
            'id', 'title', 'author', 'description', 'published_at', 'created_at', 'updated_at'
        ];
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function(){ return date('Y-m-d H:i:s'); },
            ],
        ];
    }
}
