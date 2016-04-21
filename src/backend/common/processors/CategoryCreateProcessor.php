<?php

namespace common\processors;

use common\services\CategoryService;

class CategoryCreateProcessor extends BaseCategoryProcessor
{
    /**
     * @return \common\dto\CategoryDto
     */
    protected function safeProcess()
    {
        $resultCategoryDto = CategoryService::create()
            ->spawn($this->categoryDto);

        return $resultCategoryDto;
    }

}