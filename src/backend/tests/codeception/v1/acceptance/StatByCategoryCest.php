<?php
namespace tests\codeception\v1\acceptance;

use common\classes\TimeService;
use tests\codeception\v1\AcceptanceTester;

class StatByCategoryCest extends BaseTransactionCest
{
    public function testWithoutDateConditions(AcceptanceTester $I)
    {
        $this->createSpendTransaction(100);
        $this->createSpendTransaction(200);

        $this->createIncomeTransaction(300);
        $this->createIncomeTransaction(400);

        $this->env->createCategory(['name' => 'will_not_appear_in_response']);

        $this->api
            ->statByCategory()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                $this->env->spendCategory->id => [
                    'id' => $this->env->spendCategory->id,
                    'name' => $this->env->spendCategory->name,
                    'is_income' => $this->env->spendCategory->is_income,
                    'sum' => 300,
                ],
                $this->env->incomeCategory->id => [
                    'id' => $this->env->incomeCategory->id,
                    'name' => $this->env->incomeCategory->name,
                    'is_income' => $this->env->incomeCategory->is_income,
                    'sum' => 700,
                ]
            ])
            ->checkSuccessResponseArraySize(2);
    }

    public function testWithDateConditions(AcceptanceTester $I)
    {
        $this->createSpendTransaction(100);

        $this->api
            ->statByCategory(['date_interval_from' => TimeService::getTimeAsAtom('+1 day')])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->statByCategory(['date_interval_from' => TimeService::getTimeAsAtom('-1 day')])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);

        $this->api
            ->statByCategory(['date_interval_till' => TimeService::getTimeAsAtom('-1 day')])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(0);

        $this->api
            ->statByCategory(['date_interval_till' => TimeService::getTimeAsAtom('+1 day')])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(1);
    }
}
