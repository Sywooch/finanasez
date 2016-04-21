<?php

namespace common\forms;

use common\dto\CategoryDto;
use common\processors\CategoryListProcessor;
use Yii;

class CategoryListForm extends Form
{
    /**
     * @return \common\dto\CategoryDto[]
     * @throws \Exception
     */
    public function process()
    {
        $categoryDto = CategoryDto::create()
            ->setUserId($this->getUser()->getId());

        $resultCategoryDtoArray = CategoryListProcessor::create()
            ->setCategoryDto($categoryDto)
            ->process();

        return $resultCategoryDtoArray;
    }
}