<?php

namespace v1\controllers;

use v1\requests\StatByCategoryRequest;
use Yii;

class StatByCategoryController extends BaseController
{
    protected function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

    public function actionIndex()
    {
        $request = new StatByCategoryRequest();
        return $request->process(Yii::$app->request->getBodyParams());
    }
}
