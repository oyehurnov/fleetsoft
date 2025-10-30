<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use Firebase\JWT\JWT;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    public function verbs() {
        return ['login' => ['POST']];
    }

    public function actionLogin()
    {
        $body = Yii::$app->request->getBodyParams();
        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;

        if (!$username || !$password) {
            throw new BadRequestHttpException('username and password required');
        }

        $user = User::find()->where(['username' => $username])->one();
        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

        $now = time();
        $payload = [
            'iss' => Yii::$app->params['jwtIssuer'] ?? 'yii2-app',
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + (Yii::$app->params['jwtExpire'] ?? 3600),
            'uid' => $user->id,
        ];

        $jwt = JWT::encode($payload, Yii::$app->params['jwtSecret'], 'HS256');

        return ['token' => $jwt, 'expires_in' => Yii::$app->params['jwtExpire']];
    }
}
