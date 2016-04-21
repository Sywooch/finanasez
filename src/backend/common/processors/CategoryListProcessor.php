<?php

namespace common\processors;

use common\services\CategoryService;

class CategoryListProcessor extends BaseCategoryProcessor
{
    /**
     * @return \common\dto\CategoryDto[]
     */
    protected function safeProcess()
    {
        $resultCategoryDtoArray = CategoryService::create()
            ->getForUser($this->categoryDto);

        return $resultCategoryDtoArray;
    }
}