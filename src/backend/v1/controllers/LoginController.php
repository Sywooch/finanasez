<?php

namespace v1\controllers;

use v1\requests\LoginRequest;
use Yii;

class LoginController extends BaseController
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
        $request = new LoginRequest();
        return $request->process(Yii::$app->request->post());
    }
}
