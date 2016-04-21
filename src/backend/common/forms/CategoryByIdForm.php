<?php

namespace common\forms;

use common\models\Category;
use common\repositories\CategoryRepository;
use Yii;

abstract class CategoryByIdForm extends Form
{
    public $id;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'uuid'],
            ['id', 'validateCategoryId'],
        ];
    }

    public function validateCategoryId()
    {
        if (!$this->hasErrors()) {
            $category = CategoryRepository::create()->getByIdAndUserId($this->id, $this->getUser()->getId());

            if (!$category) {
                $this->addError('id', 'Категория не найдена или принадлежит другому пользователю.');
            }
        }
    }
}