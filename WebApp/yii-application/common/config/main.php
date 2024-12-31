<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'cache' => 'cache',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'useFileTransport' => false,
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'smtp.gmail.com', // Correct SMTP host
                'username' => 'waypinpoint@gmail.com', // Your Gmail email
                'password' => 'yxqb eccz rahl vxuo', // Gmail app password
                'port' => 587, // Use 587 for TLS
                'encryption' => 'tls', // Encryption type
            ],
        ],
        //Logger to see the emails to send in frontend/runtime/mail
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['yii\symfonymailer\*'],
                    'logFile' => '@runtime/logs/mailer.log',
                ],
            ],
        ],
    ],
];