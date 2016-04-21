<?php

namespace v1\controllers;

use v1\requests\DemoLoginRequest;
use Yii;

class DemoLoginController extends BaseController
{
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

    protected function accessControlExceptActions()
    {
        return ['index'];
    }

    public function actionIndex()
    {
        $request = new DemoLoginRequest();
        return $request->process(Yii::$app->request->post());
    }
}
