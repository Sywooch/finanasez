<?php
namespace tests\codeception\v1\acceptance;

use tests\codeception\v1\AcceptanceTester;

class LoginCest extends BaseCest
{
    public function loginTest(AcceptanceTester $I)
    {
        $I->comment('Авторизация без параметров');
        $this->api->login([])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Авторизация без пароля');
        $this->api->login(['username' => 'test'])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Авторизация c ненадлежащими полями');
        $this->api->login(['blablabla' => 'blablabla'])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Авторизация c некорректным паролем');
        $this->api->login(['username' => 'test', 'password' => 'invalid'])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Авторизация c корректными данными');
        $this->api->login(['username' => 'test', 'password' => 'test'])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id' => $this->env->user->id,
                'username' => $this->env->user->username,
                'token'     => $this->env->user->token,
                'email' => $this->env->user->email,
            ]);

    }
}
