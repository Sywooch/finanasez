<?php

namespace api\modules\v1\controllers;

use api\modules\v1\controllers\MyRestController;
use yii\db\Exception;
use yii\db\IntegrityException;

abstract class MyRestActionController extends MyRestController
{

    public function actionIndex($id = null)
    {
        if($id) {
            return $this->findModelById($id);
        }

        return $this->findAllModels();
    }

    public function actionDelete($id)
    {
        $modelName = $this->getModelName();
        if(!$id || ($model = $this->findModelById($id)) === null) {
            throw new NotFoundHttpException($modelName." with this ID not found");
        }

        if(!$model->delete()) {
            return $this->responseWithCode(406, [
                'message'=>'can\'t delete this row'
           ]);
        }

        return [
            'message'=> $modelName.' with id '.$id.' DELETED.'
        ];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModelById($id);
        $this->loadModelFromPost($model);

        try {
            $saveResult = $model->save();
        } catch(Exception $ex) {
            return $this->responseWithCode(406, [
                'message'=>$ex->getMessage()
            ]);
        }

        if(!$saveResult) {

            return $this->responseWithCode(406, [
                'message'=>'Not enough params',
                'fields'=> $model->getErrors()
            ]);
        }

        return $model;
    }

    public function actionCreate()
    {
        $className = $this->modelClass;
        $modelName = $this->getModelName();
        $model = new $className();
        $this->loadModelFromPost($model);

        // name field (e.g. for category, bill) must be unique
        // if this field exists in table, check it for unique
        if(isset($model->name) && null !== $this->findModelByName($model->name)) {

            return $this->responseWithCode(406, [
                    'message' => "$modelName with this name already exists"
                ]
            );
        }

        try {
            $saveResult = $model->save();
        } catch(IntegrityException $ex) {
            return $this->responseWithCode(406, [
                'message' => 'a foreign key constraint fails',
            ]);
        } catch(Exception $ex) {
            return $this->responseWithCode(406, [
                'message'=> $ex->getMessage()
            ]);
        }

        if(!$saveResult) {
            return $this->responseWithCode(406, [
                'message'=>'Not enough params',
                'fields'=> $model->getErrors()
            ]);
        }

        return $this->responseWithCode(201, $model);


    }

}