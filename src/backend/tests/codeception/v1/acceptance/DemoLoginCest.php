<?php
namespace tests\codeception\v1\acceptance;

use Yii;
use tests\codeception\v1\AcceptanceTester;

class DemoLoginCest extends BaseCest
{
    public function loginTest(AcceptanceTester $I)
    {
        $testUser = $this->env->createUser([
            'username' => Yii::$app->params['demo_user_username'],
            'password' => Yii::$app->params['demo_user_password'],
        ]);

        $this->api
            ->demoLogin()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id'        => $testUser->id,
                'username'  => $testUser->username,
                'token'     => $testUser->token,
                'email'     => $testUser->email,
            ]);
    }
}
