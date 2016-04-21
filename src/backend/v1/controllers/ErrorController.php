<?php
namespace v1\controllers;

use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

class ErrorController extends BaseController
{
    protected function accessControlExceptActions()
    {
        return ['index'];
    }

    public function actionIndex()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $statusCode = $exception->statusCode;
        } else {
            $statusCode = 500;
        }

        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = Yii::t('yii', 'Error');
        }

        if ($statusCode) {
            $name .= " (#$statusCode)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = Yii::t('yii', 'An internal server error occurred.');
        }

        Yii::$app->response->setStatusCode($statusCode);

        return "$name: $message";
    }
}
