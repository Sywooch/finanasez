<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => '\api\modules\v1\Module'
        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ],
            'cookieValidationKey' => 'AhhjiByBBnXsParUCjmykfANlMtWWghX',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->headers->add('Access-Control-Allow-Origin', '*');
                $response->headers->add('Access-Control-Allow-Method', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->add('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Access-Token');
                $response->headers->add('Access-Control-Max-Age', '3600');
                $response->format = \yii\web\Response::FORMAT_JSON;
                if ($response->data !== null){
                    $response->data = [
                        'status' => $response->isSuccessful,
                        'code' => $response->statusCode,
                        'data' => [
                            'response' => $response->data
                        ]
                    ];
                } else

                return $response;
            },
        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,

        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>  'v1/user',
                    'pluralize' => false,
                    'tokens' => ['{id}' => '<id:\\w+>'],
                    'patterns'=> [
                        'POST login' => 'login',
                        'GET dashboard'=> 'dashboard',
                        'POST signup'=>'signup'
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>  'v1/bill',
                    'pluralize' => false,
                    'tokens' => ['{id}' => '<id:\\w+>'],
                    'patterns' => [
                        'GET {id}' => 'index',
                        'GET'=>'index',
                        'DELETE {id}' => 'delete',
                        'POST'=>'create',
                        'PUT {id}'=>'update'
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>  'v1/category',
                    'pluralize' => false,
                    'tokens' => ['{id}' => '<id:\\w+>'],
                    'patterns' => [
                        'GET {id}' => 'index',
                        'GET'=>'index',
                        'DELETE {id}' => 'delete',
                        'POST'=>'create',
                        'PUT {id}'=>'update'
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' =>  'v1/operation',
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',

                    ],
                    'patterns' => [
                        'GET {id}' => 'index',
                        'GET'=>'index',
                        'DELETE {id}' => 'delete',
                        'POST'=>'create',
                        'PUT {id}'=>'update'
                    ],
                ],

            ]

        ],
    ],
    'params' => $params,
];



