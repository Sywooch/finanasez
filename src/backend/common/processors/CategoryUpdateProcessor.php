<?php

namespace common\processors;

use common\services\CategoryService;

class CategoryUpdateProcessor extends BaseCategoryProcessor
{
    /**
     * @return \common\dto\CategoryDto
     */
    protected function safeProcess()
    {
        $resultCategoryDto = CategoryService::create()
            ->update($this->categoryDto);

        return $resultCategoryDto;
    }
}