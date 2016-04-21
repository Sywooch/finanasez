<?php

namespace common\translators;

use common\dto\CategoryDto;
use common\models\Category;

class CategoryActiveRecordToDtoTranslator
{
    /**
     * @param Category $category
     * @return CategoryDto
     */
    public static function translate(Category $category)
    {
        return CategoryDto::create()
            ->setId($category->id)
            ->setUserId($category->user_id)
            ->setName($category->name)
            ->setIsIncome($category->is_income)
            ->setCreatedAt($category->created_at)
            ->setUpdatedAt($category->updated_at);
    }
}