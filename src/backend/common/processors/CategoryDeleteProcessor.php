<?php

namespace common\processors;

use common\services\CategoryService;

class CategoryDeleteProcessor extends BaseCategoryProcessor
{
    protected function safeProcess()
    {
        CategoryService::create()
            ->delete($this->categoryDto);
    }
}