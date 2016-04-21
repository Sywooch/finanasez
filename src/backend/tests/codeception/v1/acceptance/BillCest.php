<?php
namespace tests\codeception\v1\acceptance;

use common\classes\Guid;
use common\models\Bill;
use tests\codeception\v1\AcceptanceTester;

class BillCest extends BaseCest
{
    public function viewTest(AcceptanceTester $I)
    {
        $this->api
            ->billView(Guid::random())
            ->checkResponseCodeIs(422);

        $this->api
            ->billView($this->env->bill->id)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id'        => $this->env->bill->id,
                'user_id'   => $this->env->bill->user_id,
                'name'      => $this->env->bill->name,
                'balance'   => $this->env->bill->balance,
            ]);
    }

    public function createTest(AcceptanceTester $I)
    {
        $this->api
            ->billCreate([])
            ->checkResponseCodeIs(422);

        $this->api
            ->billCreate([
                'name'      => $this->env->bill->name, // name already taken
            ])
            ->checkResponseCodeIs(422);

        $this->api
            ->billCreate([
                'balance'   => 1000,
                'name'      => 'test2',

            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id'   => $this->env->user->id,
                'name'      => 'test2',
                'balance'   => 1000,
            ]);

        $this->seeBill($I, [
            'user_id'   => $this->env->user->id,
            'name'      => 'test2',
            'balance'   => 1000,
        ]);
    }

    public function updateTest(AcceptanceTester $I)
    {
        $this->api
            ->billUpdate(Guid::random(), [])
            ->checkResponseCodeIs(422);

        $I->comment('Данные не меняются');
        $this->api
            ->billUpdate($this->env->bill->id, [
                'name'      => $this->env->bill->name,
                'balance'   => $this->env->bill->balance
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id'   => $this->env->bill->user_id,
                'name'      => $this->env->bill->name,
                'balance'   => $this->env->bill->balance,
            ]);
        $this->seeBill($I, [
            'user_id'   => $this->env->bill->user_id,
            'name'      => $this->env->bill->name,
            'balance'   => $this->env->bill->balance,
        ]);

        $I->comment('Данные изменились');
        $this->api
            ->billUpdate($this->env->bill->id, [
                'name'    => 'test2',
                'balance' => 3000
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id'   => $this->env->bill->user_id,
                'name'      => 'test2',
                'balance'   => 3000,
            ]);
        $this->seeBill($I, [
            'user_id'   => $this->env->bill->user_id,
            'name'      => 'test2',
            'balance'   => 3000,
        ]);
    }

    public function deleteTest(AcceptanceTester $I)
    {
        $this->api
            ->billDelete(Guid::random())
            ->checkResponseCodeIs(422);

        $this->seeBill($I, ['id' => $this->env->bill->id]);

        $this->api
            ->billDelete($this->env->bill->id)
            ->checkResponseCodeIs(200);

        $this->dontSeeBill($I, ['id' => $this->env->bill->id]);
    }

    public function listTest(AcceptanceTester $I)
    {
        $this->api
            ->billList()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1)
            ->checkSuccessResponse([
                $this->env->bill->id => [
                    'id' => $this->env->bill->id,
                    'user_id' => $this->env->bill->user_id,
                    'name' => $this->env->bill->name,
                    'balance' => $this->env->bill->balance,
                ],
            ]);
    }

    private function seeBill(AcceptanceTester $I, array $billParams)
    {
        $bill = Bill::findOne($billParams);
        $I->assertNotNull($bill, 'Найден счет');
    }

    private function dontSeeBill(AcceptanceTester $I, array $billParams)
    {
        $bill = Bill::findOne($billParams);
        $I->assertNull($bill, 'Счет не найден');
    }

}
