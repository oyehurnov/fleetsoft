<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Book;
use yii\filters\auth\CompositeAuth;
use app\components\JwtHttpBearerAuth;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class BooksController extends ActiveController
{
    public $modelClass = 'app\models\Book';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Authentication for create/update/delete
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'only' => ['create', 'update', 'delete'],
            'authMethods' => [
                JwtHttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }

    // override index to add pagination easily (GET /books)
    public function actionIndex()
    {
        $query = Book::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per-page', 10),
            ],
        ]);
        return $provider;
    }

    // POST /books
    public function actionCreate()
    {
        $model = new Book();
        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201);
            return $model;
        }
        return ['errors' => $model->getErrors()];
    }

    // PUT /books/{id}
    public function actionUpdate($id)
    {
        $model = Book::findOne($id);
        if (!$model) throw new \yii\web\NotFoundHttpException('Book not found');
        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save()) return $model;
        return ['errors' => $model->getErrors()];
    }

    // DELETE /books/{id}
    public function actionDelete($id)
    {
        $model = Book::findOne($id);
        if (!$model) throw new \yii\web\NotFoundHttpException('Book not found');
        $model->delete();
        Yii::$app->getResponse()->setStatusCode(204);
        return null;
    }
}
