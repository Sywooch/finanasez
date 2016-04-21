<?php

namespace v1\controllers;

use v1\requests\RegisterRequest;
use Yii;

class RegisterController extends BaseController
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
        $request = new RegisterRequest();
        return $request->process(Yii::$app->request->post());
    }
}
