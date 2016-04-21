<?php

namespace common\processors;

use common\services\CategoryService;

class CategoryViewProcessor extends BaseCategoryProcessor
{

    /**
     * @return \common\dto\CategoryDto
     */
    protected function safeProcess()
    {
        $resultCategoryDto = CategoryService::create()
            ->view($this->categoryDto);

        return $resultCategoryDto;
    }
}