<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\BadRequestHttpException;
use app\components\JwtHttpBearerAuth;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class UsersController extends Controller
{
    public $modelClass = 'app\models\User';

    public function behaviors() {
        $behaviors = parent::behaviors();
        // JSON
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [ 'application/json' => Response::FORMAT_JSON ],
        ];

        // Auth for certain actions
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'only' => ['view'],
            'authMethods' => [
                \app\components\JwtHttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }

    // POST /users  (register)
    public function actionIndex()
    {
        $data = Yii::$app->request->getBodyParams();
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$email || !$password) {
            throw new BadRequestHttpException('username, email and password are required');
        }

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->created_at = date('Y-m-d H:i:s');

        if ($user->save()) {
            \Yii::$app->getResponse()->setStatusCode(201);
            return $user;
        }

        return ['errors' => $user->getErrors()];
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->getBodyParams();

        $user = new User();
        $user->username = $data['username'] ?? null;
        $user->email = $data['email'] ?? null;

        // Map plain password to hashed value
        if (!empty($data['password'])) {
            $user->password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $user->created_at = date('Y-m-d H:i:s');

        if ($user->validate() && $user->save()) {
            Yii::$app->response->setStatusCode(201);
            return $user;
        }

        Yii::$app->response->setStatusCode(400);
        return ['errors' => $user->getErrors()];
    }

    // GET /users/{id}
    public function actionView($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new \yii\web\NotFoundHttpException('User not found');
        }
        return $user;
    }
}
