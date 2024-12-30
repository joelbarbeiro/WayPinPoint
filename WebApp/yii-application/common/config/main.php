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
            'class' => 'yii\symfonymailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'dsn' => 'smtp://waypinpoint@gmail.com', // Gmail SMTP settings
                'username' => 'waypinpoint@gmail.com', // Your Gmail email address
                'password' => 'yxqb eccz rahl vxuo', // Your Gmail password or app password
                'port' => '587', // Use 465 for SSL or 587 for TLS
                'encryption' => 'tls', // Use 'tls' if you are using port 587
                'enableTls' => true, // Required for Gmail
            ],
        ],
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
