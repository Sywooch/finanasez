<?php

namespace common\forms;

use common\dto\CategoryDto;
use common\models\Category;
use common\processors\CategoryUpdateProcessor;
use Yii;

class CategoryUpdateForm extends CategoryByIdForm
{
    public $name;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['name', 'unique', 'targetClass' => Category::className(), 'filter' => function($model) {
                /** @var \yii\db\ActiveQuery $model */
                $model
                    ->andWhere('id != :id', [':id' => $this->id])
                    ->andWhere('user_id = :user_id', [':user_id' => Yii::$app->user->identity->getId()]);

            }]
        ]);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название категории',
        ];
    }

    /**
     * @return \common\dto\CategoryDto
     * @throws \Exception
     */
    public function process()
    {
        $categoryDto = CategoryDto::create()
            ->setId($this->id)
            ->setUserId(Yii::$app->user->identity->getId())
            ->setName($this->name);

        $resultCategoryDto = CategoryUpdateProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();

        return $resultCategoryDto;
    }
}