<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host={{db_host}};dbname={{db_name}}',
            'username' => '{{db_user}}',
            'password' => '{{db_pass}}',
            'charset' => 'utf8',
        ],
        'amqp' => [
            'class' => \common\components\AMQPServer::className(),
            'host' => '{{amqp_host}}',
            'port' => intval('{{amqp_port}}'),
            'username' => '{{amqp_user}}',
            'password' => '{{amqp_pass}}'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'noreply.finansez@yandex.ru',
                'password' => '5dc8459b-4099-448a-8613-93e669c7b559',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ]
    ],
];