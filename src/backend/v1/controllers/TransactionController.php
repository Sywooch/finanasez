<?php

namespace v1\controllers;

use v1\requests\DashboardRequest;
use v1\requests\TransactionCreateRequest;
use v1\requests\TransactionDeleteRequest;
use v1\requests\TransactionListRequest;
use v1\requests\TransactionUpdateRequest;
use v1\requests\TransactionViewRequest;
use Yii;

class TransactionController extends BaseController
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
        $request = new TransactionListRequest();
        return $request->process(Yii::$app->request->getQueryParams());
    }

    public function actionCreate()
    {
        $request = new TransactionCreateRequest();
        return $request->process(Yii::$app->request->getBodyParams());
    }

    public function actionDelete()
    {
        $request = new TransactionDeleteRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }

    public function actionView()
    {
        $request = new TransactionViewRequest();
        return $request->process(Yii::$app->request->getQueryParams());
    }

    public function actionUpdate()
    {
        $request = new TransactionUpdateRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }
}
