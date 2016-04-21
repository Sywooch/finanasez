<?php

namespace v1\controllers;

use v1\requests\BillCreateRequest;
use v1\requests\BillDeleteRequest;
use v1\requests\BillUpdateRequest;
use v1\requests\BillViewRequest;
use v1\requests\CategoryCreateRequest;
use v1\requests\CategoryDeleteRequest;
use v1\requests\CategoryListRequest;
use v1\requests\CategoryUpdateRequest;
use v1\requests\CategoryViewRequest;
use Yii;

class CategoryController extends BaseController
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
        $request = new CategoryListRequest();
        return $request->process();
    }

    public function actionCreate()
    {
        $request = new CategoryCreateRequest();
        return $request->process(Yii::$app->request->getBodyParams());
    }

    public function actionDelete()
    {
        $request = new CategoryDeleteRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }

    public function actionView()
    {
        $request = new CategoryViewRequest();
        return $request->process(Yii::$app->request->getQueryParams());
    }

    public function actionUpdate()
    {
        $request = new CategoryUpdateRequest();
        return $request->process(Yii::$app->request->getQueryParams() + Yii::$app->request->getBodyParams());
    }


}
