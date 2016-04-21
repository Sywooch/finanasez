<?php
namespace tests\classes;

use Yii;
use common\models\User;
use common\models\Bill;
use common\models\Category;
use common\models\Transaction;

class Environment
{
    /** @var User */
    public $user;
    /** @var  Bill */
    public $bill;
    /** @var Category */
    public $spendCategory;
    /** @var Category */
    public $incomeCategory;

    public function setUp()
    {
        $this->cleanUp();

        $this->user = $this->createUser(['token' => 'ee851937-f08f-40e4-b42d-baf6931e34a9']);
        $this->bill = $this->createBill(['id' => '90788a44-5380-403a-a448-0e7c7de211e0']);

        $this->spendCategory = $this->createCategory(['id' => 'da3e6dbe-1b26-5eaf-2124-14d58a547e50']);
        $this->incomeCategory = $this->createCategory(['id' => 'a133cd50-a00e-99c6-aa1b-11e065a88d0c', 'is_income' => true, 'name' => 'income_test']);
    }

    private function cleanUp()
    {
        User::deleteAll();
        Transaction::deleteAll();
        Bill::deleteAll();
        Category::deleteAll();
    }

    public function createUser(array $params = [])
    {
        $user = new User();
        $user->username = 'test';
        $user->setPassword('test');
        $user->generateAuthKey();
        $user->generateToken();

        $user->setAttributes($params, false);
        if (isset($params['password'])) {
            $user->setPassword($params['password']);
        }
        $user->save();
        $user->refresh();

        return $user;
    }

    public function createBill(array $params = [])
    {
        $bill = new Bill();
        $bill->name = 'test';
        $bill->user_id = $this->user->id;

        $bill->setAttributes($params, false);
        $bill->save();
        $bill->refresh();

        return $bill;
    }

    public function createCategory(array $params = [])
    {
        $category = new Category();
        $category->user_id = $this->user->id;
        $category->name = 'spend_test';
        $category->is_income = false;

        $category->setAttributes($params, false);
        $category->save();
        $category->refresh();

        return $category;
    }
}