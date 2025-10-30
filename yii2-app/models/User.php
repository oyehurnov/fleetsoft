<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName() { return '{{%user}}'; }

    public function rules() {
        return [
            [['username', 'email', 'password_hash'], 'required'],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            ['username', 'string', 'min' => 3, 'max' => 50],
            ['password_hash', 'string'],
        ];
    }

    // registration helper
    public static function register($username, $email, $password) {
        $u = new self();
        $u->username = $username;
        $u->email = $email;
        $u->password_hash = password_hash($password, PASSWORD_DEFAULT);
        return $u->save() ? $u : null;
    }

    public function validatePassword($password) {
        return password_verify($password, $this->password_hash);
    }

    // IdentityInterface methods
    public static function findIdentity($id) { return static::findOne($id); }
    public static function findIdentityByAccessToken($token, $type = null) { return null; }
    public function getId() { return $this->id; }
    public function getAuthKey() { return null; }
    public function validateAuthKey($authKey) { return false; }

    public function fields() {
        $fields = parent::fields();
        unset($fields['password_hash']);
        return $fields;
    }
}
