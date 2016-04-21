<?php

namespace common\forms;

use common\dto\CategoryDto;
use common\processors\CategoryDeleteProcessor;
use Yii;

class CategoryDeleteForm extends CategoryByIdForm
{
    public function process()
    {
        $categoryDto = CategoryDto::create()
            ->setUserId(Yii::$app->user->identity->getId())
            ->setId($this->id);

        CategoryDeleteProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();
    }
}