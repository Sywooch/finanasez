<?php
namespace tests\codeception\v1\acceptance;

use tests\classes\Environment;
use tests\codeception\v1\AcceptanceTester;
use tests\codeception\v1\acceptance\classes\ApiTester;

abstract class BaseCest
{
    /** @var  Environment */
    protected  $env;
    /** @var  ApiTester */
    protected $api;

    public function _before(AcceptanceTester $I)
    {
        $this->env = new Environment();
        $this->env->setUp();

        $this->api = new ApiTester($I, $this->env->user->token);

    }

    public function _after(AcceptanceTester $I)
    {
    }
}
