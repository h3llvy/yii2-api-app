<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$id = 'APP ' . $_ENV['APP_NAME'];
$config = [
    'defaultRoute' => 'docs/swagger',
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'name' => 'projectName',
    'id' => $id,
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@root' => dirname(__DIR__, 2),
        '@admin' => '@root/admin',
        '@api' => '@root/api',
        '@app' => '@root/app',
        '@vendor' => '@root/vendor',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@swagger' => '@api/swagger'
    ],
    'container' => [
        'definitions' => require __DIR__ . '/definitions.php',
        'singletons' => require __DIR__ . '/singletons.php',
    ],
    'viewPath' => '@api/views',
    'components' => [
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
        'request' => [
            'cookieValidationKey' => @$_ENV['APP_KEY'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser'
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'user' => [
            'identityClass' => Users::class,
            'enableSession' => false
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
return $config;
