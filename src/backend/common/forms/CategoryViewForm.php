<?php

namespace common\forms;

use common\dto\CategoryDto;
use common\processors\CategoryViewProcessor;
use Yii;

class CategoryViewForm extends CategoryByIdForm
{
    /**
     * @return \common\dto\CategoryDto
     * @throws \Exception
     */
    public function process()
    {
        $categoryDto = CategoryDto::create()
            ->setId($this->id);

        $resultCategoryDto = CategoryViewProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();

        return $resultCategoryDto;
    }
}