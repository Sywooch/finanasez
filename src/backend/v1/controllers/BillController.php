<?php

namespace v1\controllers;

use v1\requests\BillCreateRequest;
use v1\requests\BillDeleteRequest;
use v1\requests\BillListRequest;
use v1\requests\BillUpdateRequest;
use v1\requests\BillViewRequest;
use Yii;

class BillController extends BaseController
{
    protected function verbs()
    {
        return [
            'index'  => ['GET', 'HEAD'],
            'view'   => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        $request = new BillListRequest();
        return $request->process();
    }

    public function actionCreate()
    {
        $request = new BillCreateRequest();
        return $request->process(Yii::$app->request->getBodyParams());
    }

    public function actionDelete()
    {
        $request = new BillDeleteRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }

    public function actionView()
    {
        $request = new BillViewRequest();
        return $request->process(Yii::$app->request->getQueryParams());
    }

    public function actionUpdate()
    {
        $request = new BillUpdateRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }


}
