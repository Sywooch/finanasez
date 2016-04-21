<?php

namespace v1\controllers;

use v1\requests\DashboardRequest;
use Yii;

class DashboardController extends BaseController
{
    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        $request = new DashboardRequest();
        return $request->process(Yii::$app->request->post());
    }
}
