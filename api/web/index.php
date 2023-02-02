<?php
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

define('YII_ENV', $_ENV['APP_ENV'] ?: 'prod');
define('YII_DEBUG', $_ENV['APP_ENV'] !== 'prod');

require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../../app/config/web.php';

$config['id'] = 'API ' . $_ENV['APP_NAME'];
$config['basePath'] = dirname(__DIR__, 2);
$config['controllerNamespace'] = 'api\\controllers';
$config['aliases']['@runtime'] = '@api/runtime';
$config['components']['log'] = [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'logFile' => '@api/runtime/logs/app.log',
            'except' => [
                'yii\web\HttpException:400',
                'yii\web\HttpException:401',
                'yii\web\HttpException:403',
                'yii\web\HttpException:404',
            ]
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['info'],
            'logFile' => '@api/runtime/logs/info.log',
        ],
        [
            'class' => 'yii\log\EmailTarget',
            'mailer' => 'mailer',
            'levels' => ['error', 'warning'],
            'message' => [
                'from' => [@$_ENV['ERROR_REPORT_EMAIL']],
                'to' => [@$_ENV['ERROR_REPORT_EMAIL']],
                'subject' => @$_ENV['SUBJECT_LOGS_PREFIX'] . ' '. $config['id'],
            ],
            'except' => [
                'yii\web\HttpException:400',
                'yii\web\HttpException:401',
                'yii\web\HttpException:403',
                'yii\web\HttpException:404',
            ]
        ],
    ],
];

$app = new yii\web\Application($config);

$app->run();
exit();