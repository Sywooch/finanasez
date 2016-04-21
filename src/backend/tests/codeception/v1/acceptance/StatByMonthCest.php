<?php
namespace tests\codeception\v1\acceptance;

use common\classes\Guid;
use tests\codeception\v1\AcceptanceTester;

class StatByMonthCest extends BaseTransactionCest
{
    public function testWithoutBillCondition(AcceptanceTester $I)
    {
        $this->createSpendTransaction(100);
        $this->createIncomeTransaction(200);

        $this->api
            ->statByMonth()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'spend_sum' => 100,
                'income_sum' => 200
            ])
            ->checkSuccessResponseArraySize(1);
    }

    public function testWithBillCondition(AcceptanceTester $I)
    {
        $this->createSpendTransaction(100);
        $this->createIncomeTransaction(200);

        $this->api
            ->statByMonth([
                'bill_ids' => [
                    Guid::random()
                ]
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([])
            ->checkSuccessResponseArraySize(0);
    }




}
