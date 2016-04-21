<?php
namespace tests\codeception\v1\acceptance;

use common\classes\Guid;
use tests\codeception\v1\AcceptanceTester;

class DashboardCest extends BaseCest
{
    public function queryParamMethodTest(AcceptanceTester $I)
    {
        $I->comment('Проверка работоспособности авторизации через query param');

        $errorDataProvider = [
            [],
            ['access-token' => 123],
            ['access-token' => Guid::random()]
        ];
        foreach ($errorDataProvider as $data) {
            $this->api
                ->dashboardWithoutAuthentication($data)
                ->checkResponseCodeIs(401)
                ->checkFailResponse();
        }

        $this->api
            ->dashboardWithoutAuthentication(['access-token' => $this->env->user->token])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse();
    }

    public function bearerAuthMethodTest(AcceptanceTester $I)
    {
        $I->comment('Проверка работоспособности авторизации через Bearer');

        $errorDataProvider = [
            null,
            '123',
            Guid::random()
        ];
        foreach ($errorDataProvider as $data) {
            $I->haveHttpHeader('Authorization', 'Bearer ' . $data);
            $this->api
                ->dashboardWithoutAuthentication()
                ->checkResponseCodeIs(401)
                ->checkFailResponse();
        }

        $I->haveHttpHeader('Authorization', 'Bearer '.$this->env->user->token);
        $this->api
            ->dashboardWithoutAuthentication()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse();
    }

    public function checkResponseIsCorrect(AcceptanceTester $I)
    {
        $I->comment('Проверка корректности отдачи по запросу /dashboard');

        $this->api
            ->dashboard()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse($this->env->user->getAttributes(['id', 'username', 'token', 'email']));
    }

}
