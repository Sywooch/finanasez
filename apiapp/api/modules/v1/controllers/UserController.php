<?php
namespace api\modules\v1\controllers;

use Yii;
use common\models\User;
use common\models\LoginForm;
use common\models\SignupForm;
use api\modules\v1\controllers\MyRestController;


class UserController extends MyRestController
{   
    public $modelClass = 'common\models\User';


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['login', 'signup'];

        return $behaviors;
    }


    /**
     * Route("/user/dashboard")
     * method: GET
     *
     * @return array
     */
    public function actionDashboard()
    {
        $response = [
            'username' => Yii::$app->user->identity->username,
            'access_token' => Yii::$app->user->identity->getAuthKey(),
        ];
        return $response;
    }

    /**
     * Route("/user/login")
     * method: POST
     *
     * @return array|LoginForm
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return [
                'access_token' => Yii::$app->user->identity->getAuthKey(),
                'life_time'    => Yii::$app->user->identity->getAuthKeyLifeTime()
            ];
        }
        $model->validate();

        return $model;

    }

    /**
     * Route("/user/signup")
     * method: POST
     * params: username, password
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $this->loadModelFromPost($model, false);

        try {
            $signupResult = $model->signup();
        } catch(Exception $ex) {
            return $ex->getMessage();
        }

        if (!$signupResult) {
            return $this->responseWithCode(406, $model->getErrors());
        }

        return $this->responseWithCode(201, $model);

    }
}