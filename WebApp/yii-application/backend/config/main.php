<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 1440, // Adjust as needed
            'cookieParams' => [
                'httponly' => true,
                'secure' => false, // Set to true if using HTTPS
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@backend/views' => '@backend/views',
                    '@app/views' => '@vendor/hail812/yii2-adminlte3/src/views'
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET usernames' => 'usernames',
                        'GET extras' => 'extras',
                        'GET employees' => 'employees',
                        'DELETE {username}' => 'delbyusername',
                        'POST register' => 'register',
                        'POST login' => 'login',
                        'PUT {id}' => 'edituserextras',
                        'POST photo' => 'photo',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{username}' => '<username:\\w+>', //[a-zA-Z0-9_] 1 ou + vezes (char)
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/review',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET activity/{id}' => 'activity',
                        'GET users/{id}' => 'user',
                        'POST ' => 'new',
                        'PUT edit' => 'edit'
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/cart',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET {id}' => 'cart',
                        'GET status' => 'status',
                        'GET buyers/{id}' => 'buyer',
                        'DELETE delete/{id}' => 'delete',
                        'POST addcart' => 'addcart',
                        'PUT updatecart/{id}' => 'updatecart',
                        'POST checkout/{id}' => 'checkout'
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/activity',
                    'extraPatterns' => [
                        'GET all' => 'activities',
                        'GET {id}' => 'activityview',
                        'GET calendar' => 'calendar',
                        'GET time' => 'time',
                        'GET category' => 'category',
                        'GET pictures/{id}' => 'pictures',
                        'POST createactivity' => 'createactivity',
                        'POST updateactivity/{id}' => 'updateactivity',
                        'DELETE {id}' => 'deleteactivity',
                        'POST photo' => 'photo'
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/invoice',
                    'extraPatterns' => [
                        'GET invoices/{id}' => 'invoices',
                        'GET invoice/{id}' => 'invoice',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
