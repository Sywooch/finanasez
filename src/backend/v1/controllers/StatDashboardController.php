<?php

namespace v1\controllers;

use v1\requests\StatDashboardRequest;
use Yii;

class StatDashboardController extends BaseController
{
    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        $request = new StatDashboardRequest();
        return $request->process();
    }
}
