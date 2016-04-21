<?php

namespace common\processors;

use common\dto\CategoryDto;

abstract class BaseCategoryProcessor extends BaseProcessor
{
    /** @var CategoryDto */
    protected $categoryDto;

    /**
     * @param $categoryDto
     * @return $this
     */
    public function setCategoryDto($categoryDto)
    {
        $this->categoryDto = $categoryDto;
        return $this;
    }
}