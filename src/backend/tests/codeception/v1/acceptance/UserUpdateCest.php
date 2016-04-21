<?php
namespace tests\codeception\v1\acceptance;

use common\models\Bill;
use tests\codeception\v1\AcceptanceTester;

class UserUpdateCest extends BaseCest
{
    public function updateTest(AcceptanceTester $I)
    {
        $oldPassword = $this->env->user->password_hash;

        $this->api->userUpdate($this->env->user->id, [
            'password' => 123456,
            'email' => 'novoe@email.com'
        ])->checkResponseCodeIs(200)->checkSuccessResponse([
            'email' => 'novoe@email.com'
        ]);

        $this->env->user->refresh();

        $I->assertEquals($this->env->user->email, 'novoe@email.com');
        $I->assertNotEquals($this->env->user->password_hash, $oldPassword);
    }
}
