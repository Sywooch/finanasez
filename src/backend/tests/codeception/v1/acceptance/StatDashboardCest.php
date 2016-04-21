<?php
namespace tests\codeception\v1\acceptance;

use common\classes\TimeService;
use common\dto\TransactionDto;
use common\processors\TransactionCreateProcessor;
use common\vo\SpendTransactionTypeVo;
use tests\codeception\v1\AcceptanceTester;

class StatDashboardCest extends BaseCest
{
    public function checkTodayCurrentWeekCurrentMonth(AcceptanceTester $I)
    {
        $this->spawnTransaction(100, TimeService::create()->getTimeAsAtom('now'));

        $this->api
            ->statDashboard()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'today_spend'         =>   100,
                'current_week_spend'  =>   100,
                'current_month_spend' =>   100
            ]);
    }

    public function checkYesterday(AcceptanceTester $I)
    {
        $this->spawnTransaction(100, TimeService::create()->getTimeAsAtom('-1 day'));

        $this->api
            ->statDashboard()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'yesterday_spend'       => 100,
            ]);
    }

    public function checkLastWeek(AcceptanceTester $I)
    {
        $this->spawnTransaction(100, TimeService::create()->getTimeAsAtom('last week'));

        $this->api
            ->statDashboard()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'previous_week_spend'       => 100,
            ]);
    }

    public function checkLastMonth(AcceptanceTester $I)
    {
        $this->spawnTransaction(100, TimeService::create()->getTimeAsAtom('last month'));

        $this->api
            ->statDashboard()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'previous_month_spend'     => 100,
            ]);
    }

    private function spawnTransaction($amount, $dateTime)
    {
        $transactionDto = TransactionDto::create()
            ->setUserId($this->env->user->id)
            ->setBillId($this->env->bill->id)
            ->setCategoryId($this->env->spendCategory->id)
            ->setType(new SpendTransactionTypeVo())
            ->setAmount($amount)
            ->setDatetimeLocal($dateTime);

        return TransactionCreateProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();
    }
}
