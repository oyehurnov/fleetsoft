<?php
namespace app\commands;

use yii\console\Controller;
use app\models\User;
use app\models\Book;

class SeedController extends Controller
{
    public function actionIndex()
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User([
                'username' => "user{$i}",
                'email' => "user{$i}@example.com",
                'password_hash' => password_hash("password{$i}", PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $user->save(false);
        }

        for ($i = 1; $i <= 10; $i++) {
            $book = new Book([
                'title' => "Book Title {$i}",
                'author' => "Author {$i}",
                'description' => "Test description for book {$i}.",
                'published_at' => date('Y-m-d', strtotime("2020-01-{$i}")),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $book->save(false);
        }

        echo "Seed completed!\n";
    }
}
