<?php
namespace tests\codeception\v1\acceptance;

use common\enums\TransactionTypeEnum;
use common\models\Transaction;
use tests\codeception\v1\AcceptanceTester;

class TransactionCreateCest extends BaseCest
{
    public function testCategoryId(AcceptanceTester $I)
    {
        $I->comment('Создаю транзакцию с кривыми category_id - ошибка');
        $this->api->transactionCreate([
            'category_id' => 123,
            'amount' => 100,
            'bill_id' => $this->env->bill->id,
            'type' => 'spend'
        ])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Создаю транзакцию с чужим category_id - ошибка');
        $this->api->transactionCreate([
            'category_id' => $this->env->createCategory(['user_id' => $this->getTestUser()->id])->id,
            'amount' => 100,
            'bill_id' => $this->env->bill->id,
            'type' => 'spend'
        ])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Создаю транзакцию с норм category_id - все норм');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => 100,
            'bill_id' => $this->env->bill->id,
            'type' => 'spend'
        ])->checkResponseCodeIs(200)->checkSuccessResponse();
    }

    public function testBillId(AcceptanceTester $I)
    {
        $I->comment('Создаю транзакцию с кривыми bill_id - ошибка');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => 100,
            'bill_id' => 123,
            'type' => 'spend'
        ])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Создаю транзакцию с чужим bill_id - ошибка');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => 100,
            'bill_id' => $this->env->createBill(['user_id' => $this->getTestUser()->id]),
            'type' => 'spend'
        ])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Создаю транзакцию с норм bill_id - все норм');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => 100,
            'bill_id' => $this->env->bill->id,
            'type' => 'spend'
        ])->checkResponseCodeIs(200)->checkSuccessResponse();
    }

    public function testAmount(AcceptanceTester $I)
    {
        $I->comment('Создаю транзакцию с отрицательным amount - ошибка');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => -1000,
            'bill_id' => $this->env->bill->id,
            'type' => 'spend'
        ])->checkResponseCodeIs(422)->checkFailResponse();

        $I->comment('Создаю транзакцию с amount == 0 - ошибка');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => 0,
            'bill_id' => $this->env->bill->id,
            'type' => 'spend'
        ])->checkResponseCodeIs(422)->checkFailResponse();
    }

    public function testType(AcceptanceTester $I)
    {
        $I->comment('Создаю транзакцию с кривым типом - ошибка');
        $this->api->transactionCreate([
            'category_id' => $this->env->spendCategory->id,
            'amount' => 100,
            'bill_id' => $this->env->bill->id,
            'type' => '123'
        ])->checkResponseCodeIs(422)->checkFailResponse();
    }

    public function testSpend(AcceptanceTester $I)
    {
        $I->comment('Совершаю расходную операцию и смотрю корректность записи в БД');
        $this->api->transactionCreate([
            'amount' => 100,
            'type' => 'spend',
            'category_id' => $this->env->spendCategory->id,
            'bill_id' => $this->env->bill->id,
        ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'amount' => 100,
                'type'   => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_SPEND),
                'category_id' => $this->env->spendCategory->id,
                'bill_id'=> $this->env->bill->id,
            ]);

        $this->seeTransaction($I, [
            'amount' => 100,
            'type' => TransactionTypeEnum::TYPE_SPEND,
            'bill_id' => $this->env->bill->id,
            'category_id' => $this->env->spendCategory->id,
            'user_id' => $this->env->user->id
        ]);
    }

    public function testIncome(AcceptanceTester $I)
    {
        $I->comment('Совершаю доходную операцию и смотрю корректность записи в БД');
        $this->api->transactionCreate([
            'amount' => 100,
            'type' => 'income',
            'category_id' => $this->env->incomeCategory->id,
            'bill_id' => $this->env->bill->id,
        ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'amount' => 100,
                'type'   => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_INCOME),
                'category_id' => $this->env->incomeCategory->id,
                'bill_id'=> $this->env->bill->id,
            ]);
        $this->seeTransaction($I, [
            'amount' => 100,
            'type' => TransactionTypeEnum::TYPE_INCOME,
            'bill_id' => $this->env->bill->id,
            'category_id' => $this->env->incomeCategory->id,
            'user_id' => $this->env->user->id
        ]);
    }

    public function testTransfer(AcceptanceTester $I)
    {
        $newBill = $this->env->createBill(['name' => 'test2']);

        $I->comment('Совершаю transfer операцию и смотрю корректность записи в БД');
        $this->api->transactionCreate([
            'source_bill_id' => $newBill->id,
            'bill_id' => $this->env->bill->id,
            'amount' => 100,
            'comment' => 'comment',
            'type' => 'transfer',
        ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'category_id' => null,
                'source_bill_id' => $newBill->id,
                'bill_id' => $this->env->bill->id,
                'amount' => 100,
                'comment' => 'comment',
            ]);
        $this->seeTransaction($I, [
            'type' => TransactionTypeEnum::TYPE_TRANSFER,
            'source_bill_id' => $newBill->id,
            'bill_id' => $this->env->bill->id,
            'user_id' => $this->env->user->id,
            'amount' => 100,
            'comment' => 'comment'
        ]);

        $newBill->refresh();
        $this->env->bill->refresh();

        $I->assertEquals(100, $this->env->bill->balance);
        $I->assertEquals(-100, $newBill->balance);
    }

    private function seeTransaction(AcceptanceTester $I, array $transactionParams)
    {
        $transaction = Transaction::findOne($transactionParams);
        $I->assertNotNull($transaction, 'Найдена транзакция');
    }

    private function getTestUser()
    {
        return $this->env->createUser(['username' => uniqid()]);
    }

}
