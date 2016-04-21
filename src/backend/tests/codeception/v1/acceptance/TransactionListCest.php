<?php
namespace tests\codeception\v1\acceptance;

use common\classes\TimeService;
use common\dto\TransactionDto;
use common\processors\TransactionUpdateProcessor;
use tests\codeception\v1\AcceptanceTester;

class TransactionListCest extends BaseTransactionCest
{
    public function withoutFiltersTest(AcceptanceTester $I)
    {
        $spendTransactionDto = $this->createSpendTransaction();
        $incomeTransactionDto = $this->createIncomeTransaction();
        $transferTransactionDto = $this->createTransferTransaction();

        $this->api
            ->transactionList()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                    $spendTransactionDto->getId() => [
                        'id' => $spendTransactionDto->getId(),
                    ],
                    $incomeTransactionDto->getId() => [
                        'id' => $incomeTransactionDto->getId()
                    ],
                    $transferTransactionDto->getId() => [
                        'id' => $transferTransactionDto->getId()
                    ]
            ]);
    }

    public function checkLimitOffset(AcceptanceTester $I)
    {
        $this->createSpendTransaction();
        $this->createIncomeTransaction();
        $this->createTransferTransaction();

        $this->api
            ->transactionList([
                'limit' => 1
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->api
            ->transactionList([
                'limit' => 1,
                'offset' => 3
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);
    }

    public function checkDateInterval(AcceptanceTester $I)
    {
        $this->createSpendTransaction();

        $this->api
            ->transactionList([
                'date_interval_from' => TimeService::create()->getDateTime()->modify('+1 day')->format('Y-m-d')
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->transactionList([
                'date_interval_from' => TimeService::create()->getDateTime()->modify('-1 day')->format('Y-m-d')
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->api
            ->transactionList([
                'date_interval_till' => TimeService::create()->getDateTime()->modify('-1 day')->format('Y-m-d')
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->transactionList([
                'date_interval_till' => TimeService::create()->getDateTime()->modify('+1 day')->format('Y-m-d')
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);
    }

    public function checkAmount(AcceptanceTester $I)
    {
        $this->createSpendTransaction();

        $this->api
            ->transactionList([
                'amount_from' => 500
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->api
            ->transactionList([
                'amount_from' => 1500
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->transactionList([
                'amount_till' => 1500
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->api
            ->transactionList([
                'amount_till' => 500
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

    }

    public function checkType(AcceptanceTester $I)
    {
        $this->createSpendTransaction();

        $this->api
            ->transactionList([
                'type' => 'spend'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->api
            ->transactionList([
                'type' => 'income'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->createIncomeTransaction();

        $this->api
            ->transactionList([
                'type' => 'income'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->createTransferTransaction();

        $this->api
            ->transactionList([
                'type' => 'transfer'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

    }

    public function checkComment(AcceptanceTester $I)
    {
        $transactionDto = $this->createSpendTransaction();
        $transactionDto->setComment('1test1');
        TransactionUpdateProcessor::create()
            ->setOldTransactionDto($transactionDto)
            ->setNewTransactionDto($transactionDto)
            ->process();

        $this->api
            ->transactionList([
                'comment' => 'test123'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->transactionList([
                'comment' => 'test'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);
    }

    public function checkCategoryName(AcceptanceTester $I)
    {
        $this->createSpendTransaction();

        $this->api
            ->transactionList([
                'category_name' => 'NotExistingCategoryName'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->transactionList([
                'category_name' => $this->env->spendCategory->name
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);
    }
}
