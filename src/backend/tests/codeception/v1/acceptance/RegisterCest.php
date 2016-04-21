<?php
namespace tests\codeception\v1\acceptance;

use common\models\User;
use tests\codeception\v1\AcceptanceTester;

class RegisterCest extends BaseCest
{
    public function registerTest(AcceptanceTester $I)
    {
        $I->comment('Регистрация без параметров');
        $this->api->register([])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Регистрация без пароля');
        $this->api->register(['username' => 'test1'])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Регистрация существующего пользователя');
        $this->api->register(['username' => 'test'])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Регистрация без username');
        $this->api->register(['password' => 'test'])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Регистрация с корректными параметрами');
        $this->api->register(['username' => 'test1', 'password' => 'test', 'email' => 'test@test.com'])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'token'     => User::findByUsername('test1')->token,
                'username'  => User::findByUsername('test1')->username,
                'email'     => User::findByUsername('test1')->email
            ]);
    }

}
