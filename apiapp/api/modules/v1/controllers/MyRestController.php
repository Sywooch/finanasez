<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

abstract class MyRestController extends Controller
{
    public $modelClass;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                [
                    'class' => QueryParamAuth::className(),
                    'tokenParam' => 'token'
                ]
            ]

        ];

        return $behaviors;
    }

    /**
     * Route("/|/{id}")
     * Method: GET
     *
     * @param null $id
     * @return QueryInterface|array
     */
    public function actionIndex($id = null)
    {
        if($id) {
            return $this->findModelById($id);
        }

        return $this->findAllModels();
    }

    /**
     * Route("/{id}")
     * Method: DELETE
     *
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        $modelName = $this->getModelName();
        $model = $this->findModelById($id);

        $model->delete();

        return [
            'message'=> $modelName.' with id '.$id.' DELETED.'
        ];
    }

    /**
     * Route("/update/{id}")
     * method: PUT
     *
     * @param $id
     * @return QueryInterface|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModelById($id);
        $this->loadModelFromPost($model);

        if($model->save()) {
            return $model;
        }

        return $this->responseWithCode(406, [
            'message'=>'Not enough params',
            'fields'=> $model->getErrors()
        ]);
    }

    /**
     * extracts short model name
     * e.g. common\models\Bill => Bill
     *
     * @return string
     */
    protected function getModelName()
    {
        return substr($this->modelClass, strrpos($this->modelClass, '\\')+1);
    }

    /**
     * find one model by specified ID
     * and user_id = <my id>
     *
     * @param int $id
     * @return QueryInterface
     * @throws \yii\web\NotFoundHttpException
     */
    protected  function findModelById($id)
    {
        $class = $this->modelClass;
        $model = $class::find()
            ->where(['user_id'=>Yii::$app->user->getId()])
            ->andWhere(['id'=>$id])
            ->one();
        if(null === $model) {
            throw new NotFoundHttpException("{$this->getModelName()} with this ID not found");
        }

        return $model;
    }

    /**
     * find all models for $this->modelClass
     *
     * @return array
     */
    protected  function findAllModels()
    {
        $class = $this->modelClass;

        return  $class::find()
            ->where(['user_id'=>Yii::$app->user->getId()])
            ->all();
    }

    /**
     * finds one model where user_id=<<my id>> and name=$name
     *
     * @param $name
     * @return QueryInterface
     */
    protected function findModelByName($name)
    {
        $class = $this->modelClass;

        return $class::find()
            ->where(['user_id'=>Yii::$app->user->getId()])
            ->andWhere(['name'=>$name])
            ->one();
    }

    /**
     * Loads data into model from $_POST
     * Usage: $this->loadModelFromPost($myModel);
     *
     * @param $model
     */
    protected function loadModelFromPost(&$model, $setUserId = true)
    {
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if($setUserId) {
            $model->user_id = Yii::$app->user->getId();
        }
    }

    /**
     * set response HTTP code & output message in json format
     *
     * @param integer $code
     * @param mixed $responseBody
     * @return Response
     */
    protected function responseWithCode($code, $responseBody)
    {
        Yii::$app->response->setStatusCode($code);
        return $responseBody;
    }


}