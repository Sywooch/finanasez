<?php
namespace tests\codeception\v1\acceptance;

use common\classes\Guid;
use common\dto\TransactionDto;
use common\enums\TransactionTypeEnum;
use tests\codeception\v1\AcceptanceTester;

class TransactionViewCest extends BaseTransactionCest
{
    public function notExistingTransactionTest()
    {
        $this->api
            ->transactionView(Guid::random())
            ->checkResponseCodeIs(422);
    }

    public function spendTransactionTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createSpendTransaction();

        $this->env->bill->refresh();

        $this->api
            ->transactionView($transactionDto->getId())
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id'             => $transactionDto->getId(),
                'user_id'        => $this->env->user->getId(),
                'category_id'    => $this->env->spendCategory->id,
                'bill_id'        => $this->env->bill->id,
                'amount'         => 1000,
                'type'           => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_SPEND),
                'comment'        => null,
                'source_bill_id' => null,
                'bill'           => [
                    'id'      => $this->env->bill->id,
                    'name'    => $this->env->bill->name,
                    'balance' => -1000
                ],
                'category'       => [
                    'id'        => $this->env->spendCategory->id,
                    'name'      => $this->env->spendCategory->name,
                    'is_income' => $this->env->spendCategory->is_income,
                ],
            ]);
    }

    public function incomeTransactionTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createIncomeTransaction();
        $this->env->bill->refresh();

        $this->api
            ->transactionView($transactionDto->getId())
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id'             => $transactionDto->getId(),
                'user_id'        => $this->env->user->getId(),
                'category_id'    => $this->env->incomeCategory->id,
                'bill_id'        => $this->env->bill->id,
                'amount'         => 1000,
                'type'           => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_INCOME),
                'comment'        => null,
                'source_bill_id' => null,
                'bill'           => [
                    'id'      => $this->env->bill->id,
                    'name'    => $this->env->bill->name,
                    'balance' => 1000
                ],
                'category'       => [
                    'id'        => $this->env->incomeCategory->id,
                    'name'      => $this->env->incomeCategory->name,
                    'is_income' => $this->env->incomeCategory->is_income,
                ],
            ]);
    }

    public function transferTransactionTest(AcceptanceTester $I)
    {
        /** @var TransactionDto $transactionDto */
        $transactionDto = $this->createTransferTransaction();
        $this->env->bill->refresh();

        $this->api
            ->transactionView($transactionDto->getId())
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id'             => $transactionDto->getId(),
                'user_id'        => $this->env->user->getId(),
                'category_id'    => null,
                'bill_id'        => $this->env->bill->id,
                'amount'         => 1000,
                'type'           => TransactionTypeEnum::getApiNameByCode(TransactionTypeEnum::TYPE_TRANSFER),
                'comment'        => null,
                'source_bill_id' => $transactionDto->getSourceBillId(),
                'bill'           => [
                    'id'      => $this->env->bill->id,
                    'name'    => $this->env->bill->name,
                    'balance' => 1000,
                ],
                'source_bill'    => [
                    'id'      => $transactionDto->getSourceBillId(),
                    'balance' => -1000,
                ],
            ]);
    }
}
