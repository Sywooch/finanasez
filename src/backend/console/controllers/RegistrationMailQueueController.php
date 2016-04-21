<?php

namespace console\controllers;

use common\services\AMQPQueueRegistrationMailServerService;
use Yii;
use yii\console\Controller;

class RegistrationMailQueueController extends Controller
{
    public function actionIndex()
    {
        AMQPQueueRegistrationMailServerService::create()
            ->run();
    }
}