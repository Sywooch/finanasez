<?php

namespace v1\controllers;

use v1\requests\FrontendLoggerRequest;
use Yii;

class FrontendLoggerController extends BaseController
{
    protected function verbs()
    {
        return [
            'index'  => ['POST'],
        ];
    }

    protected function accessControlExceptActions()
    {
        return ['index'];
    }


    public function actionIndex()
    {
        $request = new FrontendLoggerRequest();
        return $request->process(Yii::$app->request->getBodyParams());
    }
}
