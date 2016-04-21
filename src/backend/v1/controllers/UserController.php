<?php

namespace v1\controllers;

use v1\requests\UserUpdateRequest;
use Yii;

class UserController extends BaseController
{
    protected function verbs()
    {
        return [
            'update' => ['PUT', 'PATCH'],
        ];
    }

    public function actionUpdate()
    {
        $request = new UserUpdateRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }
}
