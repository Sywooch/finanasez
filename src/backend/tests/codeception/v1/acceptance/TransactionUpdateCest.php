<?php
namespace tests\codeception\v1\acceptance;

use common\dto\TransactionDto;
use common\enums\TransactionTypeEnum;
use common\models\Transaction;
use tests\codeception\v1\AcceptanceTester;

class TransactionUpdateCest extends BaseTransactionCest
{
    public function updateSpendTransactionTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createSpendTransaction();
        $this->ensureBalanceIs($I, -1000);

        $data = [
            'amount' => 500,
            'comment' => 'new comment',
        ];
        $this->api
            ->transactionUpdate($transactionDto->getId(), $data)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse(['type' => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_SPEND)] + $data);

        $this->seeTransaction($I, ['type' => TransactionTypeEnum::TYPE_SPEND, 'user_id' => $this->env->user->id] + $data);
        $this->ensureBalanceIs($I, -500);
    }

    public function updateSpendTransactionWithTypeChangeTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createSpendTransaction();
        $this->ensureBalanceIs($I, -1000);

        $data = [
            'amount' => 500,
            'type' => 'income',
            'category_id' => $this->env->incomeCategory->id,
            'comment' => 'new comment',
        ];
        $this->api
            ->transactionUpdate($transactionDto->getId(), $data)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse(['type' => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_INCOME)] + $data);

        $this->seeTransaction($I, ['type' => TransactionTypeEnum::TYPE_INCOME, 'user_id' => $this->env->user->id] + $data);
        $this->ensureBalanceIs($I, 500);
    }

    public function updateIncomeTransactionTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createIncomeTransaction();
        $this->ensureBalanceIs($I, 1000);

        $data = [
            'amount' => 500,
            'comment' => 'new comment',
        ];
        $this->api
            ->transactionUpdate($transactionDto->getId(), $data)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse(['type' => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_INCOME)] + $data);

        $this->seeTransaction($I, ['type' => TransactionTypeEnum::TYPE_INCOME, 'user_id' => $this->env->user->id] + $data);
        $this->ensureBalanceIs($I, 500);
    }

    public function updateIncomeTransactionWithTypeChangeTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createIncomeTransaction();
        $this->ensureBalanceIs($I, 1000);

        $data = [
            'amount' => 500,
            'type' => 'spend',
            'category_id' => $this->env->spendCategory->id,
            'comment' => 'new comment',
        ];
        $this->api
            ->transactionUpdate($transactionDto->getId(), $data)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse(['type' => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_SPEND)] + $data);

        $this->seeTransaction($I, ['type' => TransactionTypeEnum::TYPE_SPEND, 'user_id' => $this->env->user->id] + $data);
        $this->ensureBalanceIs($I, -500);
    }

    public function updateTransferTransactionTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createTransferTransaction();
        $this->ensureBalanceIs($I, 1000);
        $I->assertEquals(-1000, $this->getTestBill()->balance);

        $data = [
            'amount' => 500,
            'comment' => 'new comment',
        ];
        $this->api
            ->transactionUpdate($transactionDto->getId(), $data)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse(['type' => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_TRANSFER)] + $data);

        $this->seeTransaction($I, ['type' => TransactionTypeEnum::TYPE_TRANSFER, 'user_id' => $this->env->user->id] + $data);
        $this->ensureBalanceIs($I, 500);
        $I->assertEquals(-500, $this->getTestBill()->balance);
    }

    public function ensureAdditionalFieldsChangesTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createSpendTransaction();

        $data = [
            'date' => '01.01.2015 00:00:00',
            'category_id' => $this->getTestSpendCategory()->id,
        ];

        $this->api
            ->transactionUpdate($transactionDto->getId(), $data)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id' => $this->env->user->id,
                'date' => '01.01.2015 00:00:00',
                'category_id' => $this->getTestSpendCategory()->id,
            ]);

        $this->seeTransaction($I, [
                'user_id' => $this->env->user->id,
                'datetime_local' => '2015-01-01 00:00:00',
                'category_id' => $this->getTestSpendCategory()->id,
        ]);
    }

    private function seeTransaction(AcceptanceTester $I, array $transactionParams)
    {
        $transaction = Transaction::findOne($transactionParams);
        $I->assertNotNull($transaction, 'Найдена транзакция');
    }

    private function ensureBalanceIs(AcceptanceTester $I, $balance)
    {
        $this->env->bill->refresh();
        $I->assertEquals($balance, $this->env->bill->balance);
    }


}
