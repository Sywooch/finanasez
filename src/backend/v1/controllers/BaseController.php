<?php
namespace v1\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

abstract class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::className()
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator' => [
                'class'     => CompositeAuth::className(),
                'only'      => $this->accessControlOnlyActions(),
                'except'    => $this->accessControlExceptActions(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ],
            ],
        ];
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);

        if (Yii::$app->response->isSuccessful) {
            return $this->successResponse($result);
        }
        return $this->failureResponse($result);
    }

    /**
     * @return array if defined, all actions except these will require authentication
     */
    protected function accessControlExceptActions()
    {
        return [];
    }

    /**
     * @return array if defined, only these actions will require authentication
     */
    protected function accessControlOnlyActions()
    {
        return [];
    }

    public function beforeAction($action)
    {
        if(Yii::$app->request->method === 'OPTIONS') {
            $this->handlePreflightRequest();
        }

        $result = false;
        try {
            $result = parent::beforeAction($action);
        } catch (UnauthorizedHttpException $e) {
            $this->handleUnauthorizedRequest();
        }
        return $result;
    }

    private function successResponse($data)
    {
        return array_merge(['success' => true, 'data' => $data]);
    }

    private function failureResponse($description)
    {
        return array_merge(['success' => false, 'error_description' => $description]);
    }

    private function handlePreflightRequest()
    {
        Yii::$app->response->headers->set('Access-Control-Allow-Credentials', 'true');
        Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        Yii::$app->response->headers->set('Access-Control-Allow-Headers','Origin, X-Requested-With, Content-Type, Accept, Authorization');
        Yii::$app->response->content = null;
        Yii::$app->end();
    }

    private function handleUnauthorizedRequest()
    {
        Yii::$app->response->setStatusCode(401);
        Yii::$app->response->data = $this->failureResponse('Неверные авторизационные данные');
        Yii::$app->end();
    }
}
