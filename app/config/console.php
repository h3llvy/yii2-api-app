<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$id = 'CONSOLE ' . $_ENV['APP_NAME'];
$config = [
    'id' => $id,
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@root' => dirname(__DIR__, 2),
        '@api' => '@root/api',
        '@app' => '@root/app',
        '@swagger' => '@api/swagger',
        '@vendor' => __DIR__ . '/../../vendor',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'schedule' => \omnilight\scheduling\Schedule::class,
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => @$_ENV['ERROR_REPORT_EMAIL_PASSWORD_SMTP_HOST'],
                'username' => @$_ENV['ERROR_REPORT_EMAIL'],
                'password' => @$_ENV['ERROR_REPORT_EMAIL_PASSWORD'],
                'port' => @$_ENV['ERROR_REPORT_EMAIL_PASSWORD_SMTP_PORT'],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/console.log',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/info.log',
                    'levels' => ['info'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error', 'warning'],
                    'message' => [
                        'from' => [@$_ENV['ERROR_REPORT_EMAIL']],
                        'to' => [@$_ENV['ERROR_REPORT_EMAIL']],
                        'subject' => @$_ENV['SUBJECT_LOGS_PREFIX'] . ' ' . $id,
                    ],
                ],
            ],
        ],
        'db' => $db
    ],
    'params' => $params,
    'container' => [
        'definitions' => require __DIR__ . '/definitions.php',
        'singletons' => require __DIR__ . '/singletons.php',
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'ns' => 'app\entities'
            ]
        ]
    ];
}

return $config;
