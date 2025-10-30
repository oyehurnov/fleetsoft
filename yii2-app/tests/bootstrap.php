<?php
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

// Use in-memory SQLite for speed (optional)
if (isset($config['components']['db'])) {
    $config['components']['db'] = [
        'class' => 'yii\db\Connection',
        'dsn' => 'sqlite::memory:',
    ];
}

(new yii\web\Application($config));
