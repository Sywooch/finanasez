<?php

namespace common\forms;

use Yii;

class DemoLoginForm extends LoginForm
{
    public function init()
    {
        parent::init();
        $this->username = Yii::$app->params['demo_user_username'];
        $this->password = Yii::$app->params['demo_user_password'];
    }
}