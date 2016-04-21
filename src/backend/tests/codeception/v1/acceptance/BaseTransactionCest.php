<?php
namespace tests\codeception\v1\acceptance;

use common\dto\TransactionDto;
use common\models\Bill;
use common\models\Category;
use common\processors\IncomeTransactionCreateProcessor;
use common\processors\SpendTransactionCreateProcessor;
use common\processors\TransferTransactionCreateProcessor;
use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use common\vo\TransferTransactionTypeVo;

abstract class BaseTransactionCest extends BaseCest
{
    /**
     * @param int $amount
     * @return TransactionDto
     * @throws \Exception
     */
    protected function createSpendTransaction($amount = 1000)
    {
        $transactionDto = TransactionDto::create()
            ->setUserId($this->env->user->id)
            ->setBillId($this->env->bill->id)
            ->setCategoryId($this->env->spendCategory->id)
            ->setType(new SpendTransactionTypeVo())
            ->setAmount($amount);

        return SpendTransactionCreateProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();
    }

    /**
     * @param int $amount
     * @return TransactionDto
     * @throws \Exception
     */
    protected function createIncomeTransaction($amount = 1000)
    {
        $transactionDto = TransactionDto::create()
            ->setUserId($this->env->user->id)
            ->setBillId($this->env->bill->id)
            ->setCategoryId($this->env->incomeCategory->id)
            ->setType(new IncomeTransactionTypeVo())
            ->setAmount($amount);

        return IncomeTransactionCreateProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();
    }

    /**
     * @param int $amount
     * @return TransactionDto
     * @throws \Exception
     */
    protected function createTransferTransaction($amount = 1000)
    {
        $transactionDto = TransactionDto::create()
            ->setUserId($this->env->user->id)
            ->setBillId($this->env->bill->id)
            ->setSourceBillId($this->getTestBill()->id)
            ->setType(new TransferTransactionTypeVo())
            ->setAmount($amount);

        return TransferTransactionCreateProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();
    }


    /**
     * @return Bill
     */
    protected function getTestBill()
    {
        $testBill = Bill::findOne(['name' => 'test2']);
        if ($testBill === null) {
            $testBill = $this->env->createBill(['name' => 'test2']);
        }

        return $testBill;
    }

    /**
     * @return Category
     */
    protected function getTestSpendCategory()
    {
        $testSpendCategory = Category::findOne(['name' => 'test2']);
        if ($testSpendCategory === null) {
            $testSpendCategory = $this->env->createCategory(['name' => 'test2']);
        }

        return $testSpendCategory;
    }
}
