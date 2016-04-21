<?php

namespace common\processors;

use common\dto\BillDto;
use common\dto\CategoryDto;
use common\services\AMQPQueueRegistrationMailClientService;
use Yii;

class UserCreateTriggerRunProcessor extends BaseUserProcessor
{
    protected function safeProcess()
    {
        $this->createMainBill();
        $this->createMainSpendCategory();
        $this->createMainIncomeCategory();
        $this->sendRegistrationMail();
    }

    private function createMainBill()
    {
        $billDto = BillDto::create()
            ->setUserId($this->userDto->getId())
            ->setName('Основной счет')
            ->setBalance(0);

        BillCreateProcessor::create()
            ->setBillDto($billDto)
            ->process();
    }

    private function createMainSpendCategory()
    {
        $categoryDto = CategoryDto::create()
            ->setUserId($this->userDto->getId())
            ->setIsIncome(false)
            ->setName('Основная расходная категория')
            ->setIsIncome(false);

        CategoryCreateProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();
    }

    private function createMainIncomeCategory()
    {
        $categoryDto = CategoryDto::create()
            ->setUserId($this->userDto->getId())
            ->setIsIncome(false)
            ->setName('Основная доходная категория')
            ->setIsIncome(true);

        CategoryCreateProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();
    }

    private function sendRegistrationMail()
    {
        AMQPQueueRegistrationMailClientService::create()
            ->send($this->userDto);
    }
}