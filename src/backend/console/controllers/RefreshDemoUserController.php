<?php

namespace console\controllers;

use common\classes\TimeService;
use common\dto\BillDto;
use common\dto\CategoryDto;
use common\dto\TransactionDto;
use common\dto\UserDto;
use common\models\Bill;
use common\models\Category;
use common\models\Transaction;
use common\processors\BillCreateProcessor;
use common\processors\CategoryCreateProcessor;
use common\processors\TransactionCreateProcessor;
use common\processors\UserCreateTriggerRunProcessor;
use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use Yii;
use yii\console\Controller;

class RefreshDemoUserController extends Controller
{
    public function actionIndex()
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $this->clearDemoUserTables();
            $this->runRegistrationTrigger();
            $this->createBill();
            $this->createCategories();
            $this->performOperations();

            $dbTransaction->commit();
        } catch (\Exception $e) {
            $dbTransaction->rollBack();
            throw $e;
        }
    }

    private function clearDemoUserTables()
    {
        Transaction::deleteAll(['user_id' => $this->getDemoUserId()]);
        Category::deleteAll(['user_id' => $this->getDemoUserId()]);
        Bill::deleteAll(['user_id' => $this->getDemoUserId()]);
    }

    private function runRegistrationTrigger()
    {
        $userDto = UserDto::create()
            ->setId($this->getDemoUserId());

        UserCreateTriggerRunProcessor::create()
            ->setUserDto($userDto)
            ->process();
    }

    private function createBill()
    {
        $billDto = BillDto::create()
            ->setUserId($this->getDemoUserId())
            ->setName('Карточка альфа-банк')
            ->setBalance(50000);

        BillCreateProcessor::create()
            ->setBillDto($billDto)
            ->process();
    }

    private function createCategories()
    {
        $incomeCategoryDto = CategoryDto::create()
            ->setUserId($this->getDemoUserId())
            ->setIsIncome(false)
            ->setName('Продукты');

        CategoryCreateProcessor::create()
            ->setCategoryDto($incomeCategoryDto)
            ->process();

        $incomeCategoryDto = CategoryDto::create()
            ->setUserId($this->getDemoUserId())
            ->setIsIncome(true)
            ->setName('Зарплата');

        CategoryCreateProcessor::create()
            ->setCategoryDto($incomeCategoryDto)
            ->process();
    }

    private function performOperations()
    {
        for ($i = 0; $i < 60; $i++) {
            $dateTimeLocal = TimeService::create()->asAtom('-' . mt_rand(0, 50) . ' days -' . mt_rand(0, 23) . ' hours - '. mt_rand(0, 59) . ' min');

            if (rand() % 2 === 0) {
                $transactionType = new SpendTransactionTypeVo();
                $isIncome = false;
            } else {
                $transactionType = new IncomeTransactionTypeVo();
                $isIncome = true;
            }

            $categoryId = Category::find()
                ->andWhere(['user_id' => $this->getDemoUserId()])
                ->andWhere(['is_income' => $isIncome])
                ->orderBy('random()')
                ->select('id')
                ->scalar();

            $billId = Bill::find()
                ->andWhere(['user_id' => $this->getDemoUserId()])
                ->orderBy('random()')
                ->select('id')
                ->scalar();

            $transactionDto = TransactionDto::create()
                ->setUserId($this->getDemoUserId())
                ->setType($transactionType)
                ->setAmount(mt_rand(100, 15000))
                ->setBillId($billId)
                ->setCategoryId($categoryId)
                ->setDatetimeLocal($dateTimeLocal);

            TransactionCreateProcessor::create()
                ->setTransactionDto($transactionDto)
                ->process();
        }
    }

    /**
     * @return string
     */
    private function getDemoUserId()
    {
        return Yii::$app->params['demo_user_id'];
    }
}