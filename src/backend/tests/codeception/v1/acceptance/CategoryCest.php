<?php
namespace tests\codeception\v1\acceptance;

use common\classes\Guid;
use common\models\Category;
use tests\codeception\v1\AcceptanceTester;

class CategoryCest extends BaseCest
{
    public function viewTest(AcceptanceTester $I)
    {
        $this->api
            ->categoryView(Guid::random())
            ->checkResponseCodeIs(422);

        $this->api
            ->categoryView($this->env->spendCategory->id)
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'id'        => $this->env->spendCategory->id,
                'user_id'   => $this->env->spendCategory->user_id,
                'name'      => $this->env->spendCategory->name,
                'is_income' => $this->env->spendCategory->is_income,
            ]);
    }

    public function createTest(AcceptanceTester $I)
    {
        $this->api
            ->categoryCreate([])
            ->checkResponseCodeIs(422);

        $this->api
            ->categoryCreate([
                'is_income' => true,
                'name'      => $this->env->spendCategory->name, // name already taken
            ])
            ->checkResponseCodeIs(422);

        $this->api
            ->categoryCreate([
                'is_income' => true,
                'name'      => 'test2',

            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id'   => $this->env->user->id,
                'name'      => 'test2',
                'is_income' => true,
            ]);

        $this->seeCategory($I, [
            'user_id'   => $this->env->user->id,
            'name'      => 'test2',
            'is_income' => true,
        ]);
    }


    public function updateTest(AcceptanceTester $I)
    {
        $this->api
            ->categoryUpdate(Guid::random(), [])
            ->checkResponseCodeIs(422);

        $I->comment('Данные не меняются');
        $this->api
            ->categoryUpdate($this->env->spendCategory->id, [
                'name' => $this->env->spendCategory->name
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id'   => $this->env->spendCategory->user_id,
                'name'      => $this->env->spendCategory->name,
                'is_income' => $this->env->spendCategory->is_income,
            ]);
        $this->seeCategory($I, [
            'user_id'   => $this->env->spendCategory->user_id,
            'name'      => $this->env->spendCategory->name,
            'is_income' => $this->env->spendCategory->is_income,
        ]);

        $I->comment('Данные изменились');
        $this->api
            ->categoryUpdate($this->env->spendCategory->id, [
                'name' => 'test2'
            ])
            ->checkResponseCodeIs(200)
            ->checkSuccessResponse([
                'user_id'   => $this->env->spendCategory->user_id,
                'name'      => 'test2',
                'is_income' => $this->env->spendCategory->is_income,
            ]);
        $this->seeCategory($I, [
            'user_id'   => $this->env->spendCategory->user_id,
            'name'      => 'test2',
            'is_income' => $this->env->spendCategory->is_income,
        ]);
    }

    public function deleteTest(AcceptanceTester $I)
    {
        $this->api
            ->categoryDelete(Guid::random())
            ->checkResponseCodeIs(422);

        $this->seeCategory($I, ['id' => $this->env->spendCategory->id]);

        $this->api
            ->categoryDelete($this->env->spendCategory->id)
            ->checkResponseCodeIs(200);

        $this->dontSeeCategory($I, ['id' => $this->env->spendCategory->id]);
    }

    public function listTest(AcceptanceTester $I)
    {
        $this->api
            ->categoryList()
            ->checkResponseCodeIs(200)
            ->checkSuccessResponseArraySize(2)
            ->checkSuccessResponse([
                $this->env->spendCategory->id => [
                    'id' => $this->env->spendCategory->id,
                    'user_id' => $this->env->spendCategory->user_id,
                    'name' => $this->env->spendCategory->name,
                    'is_income' => $this->env->spendCategory->is_income,
                ],
                $this->env->incomeCategory->id => [
                    'id' => $this->env->incomeCategory->id,
                    'user_id' => $this->env->incomeCategory->user_id,
                    'name' => $this->env->incomeCategory->name,
                    'is_income' => $this->env->incomeCategory->is_income,
                ]
            ]);
    }

    private function seeCategory(AcceptanceTester $I, array $categoryParams)
    {
        $category = Category::findOne($categoryParams);
        $I->assertNotNull($category, 'Найдена категория');
    }

    private function dontSeeCategory(AcceptanceTester $I, array $categoryParams)
    {
        $category = Category::findOne($categoryParams);
        $I->assertNull($category, 'Категория не найдена');
    }

}
