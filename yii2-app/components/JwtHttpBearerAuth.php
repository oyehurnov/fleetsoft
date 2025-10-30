<?php
namespace app\components;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\models\User;

class JwtHttpBearerAuth extends AuthMethod
{
    public $header = 'Authorization';
    public $pattern = '/^Bearer\s+(.*?)$/';

    public function authenticate($user, $request, $response)
    {
        $headers = $request->getHeaders();
        $authHeader = $headers->get($this->header);

        if ($authHeader !== null && preg_match($this->pattern, $authHeader, $matches)) {
            $token = $matches[1];
            try {
                $secret = Yii::$app->params['jwtSecret'];
                $decoded = (array) JWT::decode($token, new Key($secret, 'HS256'));
            } catch (\Exception $e) {
                throw new UnauthorizedHttpException('Invalid token: ' . $e->getMessage());
            }

            if (!isset($decoded['uid'])) {
                throw new UnauthorizedHttpException('Token missing user id');
            }

            $identity = User::findOne($decoded['uid']);
            if ($identity === null) {
                throw new UnauthorizedHttpException('User not found');
            }

            return $identity;
        }

        return null;
    }

    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', 'Bearer realm="api"');
    }
}
