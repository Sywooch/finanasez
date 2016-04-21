<?php

namespace common\forms;

use common\dto\CategoryDto;
use common\models\Category;
use common\processors\CategoryCreateProcessor;
use Yii;

class CategoryCreateForm extends Form
{
    public $name;
    public $is_income;

    public function rules()
    {
        return [
            [['name', 'is_income'], 'required'],
            ['name', 'unique', 'targetClass' => Category::className(), 'filter' => ['user_id' => $this->getUser()->getId()]],
            ['is_income', 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название категории',
            'is_income' => 'Тип'
        ];
    }

    /**
     * @return \common\dto\CategoryDto
     * @throws \Exception
     */
    public function process()
    {
        $categoryDto = CategoryDto::create()
            ->setUserId($this->getUser()->getId())
            ->setName($this->name)
            ->setIsIncome($this->is_income);

        $resultCategoryDto = CategoryCreateProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();

        return $resultCategoryDto;
    }
}