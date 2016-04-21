<?php

namespace v1\controllers;

use v1\requests\StatByMonthRequest;
use Yii;

class StatByMonthController extends BaseController
{
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

    public function actionIndex()
    {
        $request = new StatByMonthRequest();
        return $request->process(Yii::$app->request->getBodyParams());
    }
}
