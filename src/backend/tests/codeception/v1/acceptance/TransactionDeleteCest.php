<?php
namespace tests\codeception\v1\acceptance;

use common\models\Bill;
use common\models\Transaction;
use common\processors\IncomeTransactionCreateProcessor;
use common\processors\SpendTransactionCreateProcessor;
use common\processors\TransferTransactionCreateProcessor;
use tests\codeception\v1\AcceptanceTester;

class TransactionDeleteCest extends BaseTransactionCest
{
    public function deleteSpendTransactionTest(AcceptanceTester $I)
    {
        /** @var \common\dto\TransactionDto $transactionDto */
        $transactionDto = $this->createSpendTransaction();

        $this->seeTransaction($I, ['id' => $transactionDto->getId()]);
        $this->ensureBalanceIs($I, -1000);

        $this->api
            ->transactionDelete($transactionDto->getId())
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse();

        $this->dontSeeTransaction($I, ['id' => $transactionDto->getId()]);
        $this->ensureBalanceIs($I, 0);
    }

    public function deleteIncomeTransactionTest(AcceptanceTester $I)
    {
        /** @var \common\dto\TransactionDto $transactionDto */
        $transactionDto = $this->createIncomeTransaction();

        $this->seeTransaction($I, ['id' => $transactionDto->getId()]);
        $this->ensureBalanceIs($I, 1000);

        $this->api
            ->transactionDelete($transactionDto->getId())
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse();

        $this->dontSeeTransaction($I, ['id' => $transactionDto->getId()]);
        $this->ensureBalanceIs($I, 0);
    }

    public function deleteTransferTransactionTest(AcceptanceTester $I)
    {
        /** @var \common\dto\TransactionDto $transactionDto */
        $transactionDto = $this->createTransferTransaction();

        $this->seeTransaction($I, ['id' => $transactionDto->getId()]);
        $this->ensureBalanceIs($I, 1000);

        $this->api
            ->transactionDelete($transactionDto->getId())
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse();

        $this->dontSeeTransaction($I, ['id' => $transactionDto->getId()]);

        $this->ensureBalanceIs($I, 0);
        $I->assertEquals(0, Bill::findOne(['name' => 'test2'])->balance);
    }

    private function seeTransaction(AcceptanceTester $I, array $transactionParams)
    {
        $transaction = Transaction::findOne($transactionParams);
        $I->assertNotNull($transaction, 'Найдена транзакция');
    }

    private function dontSeeTransaction(AcceptanceTester $I, array $transactionParams)
    {
        $transaction = Transaction::findOne($transactionParams);
        $I->assertNull($transaction, 'Транзакция не найдена');
    }

    private function ensureBalanceIs(AcceptanceTester $I, $balance)
    {
        $this->env->bill->refresh();
        $I->assertEquals($balance, $this->env->bill->balance);
    }
}
