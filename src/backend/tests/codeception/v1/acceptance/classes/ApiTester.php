<?php
namespace tests\codeception\v1\acceptance\classes;

use tests\codeception\v1\AcceptanceTester;

class ApiTester
{
    /** @var  AcceptanceTester */
    private $I;
    /** @var  string */
    private $token;

    /**
     * ApiTester constructor.
     * @param AcceptanceTester $I
     * @param string $token
     */
    public function __construct(AcceptanceTester $I, $token)
    {
        $this->I = $I;
        $this->token = $token;
    }

    public function login($params)
    {
        $this->I->sendPOST('/login', $params);
        return $this;
    }

    public function demoLogin()
    {
        $this->I->sendPOST('/demo-login');
        return $this;
    }

    public function register($params)
    {
        $this->I->sendPOST('/register', $params);
        return $this;
    }

    public function dashboard()
    {
        $this->setAuthentication();
        return $this->dashboardWithoutAuthentication();
    }

    public function dashboardWithoutAuthentication($params = [])
    {
        $this->I->sendGET('/dashboard', $params);
        return $this;
    }

    public function transactionCreate($params)
    {
        $this->setAuthentication();
        $this->I->sendPOST('/transaction', $params);
        return $this;
    }

    public function transactionDelete($id)
    {
        $this->setAuthentication();
        $this->I->sendDELETE('/transaction/' . $id);
        return $this;
    }

    public function transactionUpdate($id, $params)
    {
        $this->setAuthentication();
        $this->I->sendPUT('/transaction/' . $id, $params);
        return $this;
    }

    public function transactionView($id)
    {
        $this->setAuthentication();
        $this->I->sendGET('/transaction/' . $id);
        return $this;
    }

    public function transactionList($params = [])
    {
        $this->setAuthentication();
        $this->I->sendGET('/transaction', $params);
        return $this;
    }

    public function billCreate($params)
    {
        $this->setAuthentication();
        $this->I->sendPOST('/bill', $params);
        return $this;
    }

    public function billView($id)
    {
        $this->setAuthentication();
        $this->I->sendGET('/bill/' . $id);
        return $this;
    }

    public function billUpdate($id, $params)
    {
        $this->setAuthentication();
        $this->I->sendPUT('/bill/' . $id, $params);
        return $this;
    }

    public function billDelete($id)
    {
        $this->setAuthentication();
        $this->I->sendDELETE('/bill/' . $id);
        return $this;
    }

    public function billList()
    {
        $this->setAuthentication();
        $this->I->sendGET('/bill');
        return $this;
    }


    public function categoryCreate($params)
    {
        $this->setAuthentication();
        $this->I->sendPOST('/category', $params);
        return $this;
    }

    public function categoryView($id)
    {
        $this->setAuthentication();
        $this->I->sendGET('/category/' . $id);
        return $this;
    }

    public function categoryUpdate($id, $params)
    {
        $this->setAuthentication();
        $this->I->sendPUT('/category/' . $id, $params);
        return $this;
    }

    public function categoryDelete($id)
    {
        $this->setAuthentication();
        $this->I->sendDELETE('/category/' . $id);
        return $this;
    }

    public function categoryList()
    {
        $this->setAuthentication();
        $this->I->sendGET('/category');
        return $this;
    }

    public function userUpdate($id, $params)
    {
        $this->setAuthentication();
        $this->I->sendPUT('/user/' . $id, $params);
        return $this;
    }

    public function statDashboard()
    {
        $this->setAuthentication();
        $this->I->sendGET('/stat-dashboard');
        return $this;
    }

    public function statByMonth($params = [])
    {
        $this->setAuthentication();
        $this->I->sendPOST('/stat-by-month', $params);
        return $this;
    }

    public function statByCategory($params = [])
    {
        $this->setAuthentication();
        $this->I->sendPOST('/stat-by-category', $params);
        return $this;
    }

    public function checkResponseCodeIs($code)
    {
        $this->I->seeResponseCodeIs($code);
        return $this;
    }

    public function checkSuccessResponse(array $data = [])
    {
        $this->I->seeResponseContainsJson(['success' => true] + ($data ? ['data' => $data] : []));
        return $this;
    }

    public function checkSuccessResponseArraySize($arraySize)
    {
        $plainResponse = $this->I->grabResponse();
        $responseAsArray = json_decode($plainResponse, true);
        $this->I->assertTrue(is_array($responseAsArray));
        $this->I->assertEquals(count($responseAsArray['data']), $arraySize);
        return $this;

    }

    public function checkFailResponse(array $errorDescription = [])
    {
        $this->I->seeResponseContainsJson(['success' => false] + ($errorDescription ? ['error_description' => $errorDescription] : []));
        return $this;
    }

    private function setAuthentication()
    {
        $this->I->haveHttpHeader('Authorization', 'Bearer ' . $this->token);
    }
}