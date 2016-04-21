<?php

namespace common\services;

use common\dto\CategoryDto;
use common\models\Category;
use common\repositories\CategoryRepository;
use common\repositories\UserRepository;
use common\translators\CategoryActiveRecordToDtoTranslator;

class CategoryService extends BaseService
{
    /**
     * @param CategoryDto $categoryDto
     * @return CategoryDto
     */
    public function spawn(CategoryDto $categoryDto)
    {
        $categoryAr = new Category();
        $this->fillModel($categoryDto, $categoryAr);

        $categoryAr->save();
        $categoryAr->refresh();

        $resultCategoryDto = CategoryActiveRecordToDtoTranslator::translate($categoryAr);

        return $resultCategoryDto;
    }

    /**
     * @param CategoryDto $categoryDto
     * @return CategoryDto
     */
    public function update(CategoryDto $categoryDto)
    {
        $categoryAr = CategoryRepository::create()
            ->get($categoryDto->getId());
        $this->fillModel($categoryDto, $categoryAr);

        $categoryAr->save();
        $categoryAr->refresh();

        $resultCategoryDto = CategoryActiveRecordToDtoTranslator::translate($categoryAr);

        return $resultCategoryDto;
    }

    /**
     * @param CategoryDto $categoryDto
     */
    public function delete(CategoryDto $categoryDto)
    {
        CategoryRepository::create()
            ->delete($categoryDto->getId());
    }

    /**
     * @param CategoryDto $categoryDto
     * @return CategoryDto
     */
    public function view(CategoryDto $categoryDto)
    {
        $categoryAr =  CategoryRepository::create()
            ->get($categoryDto->getId());

        $resultCategoryDto = CategoryActiveRecordToDtoTranslator::translate($categoryAr);

        return $resultCategoryDto;
    }

    /**
     * @param CategoryDto $categoryDto
     * @return array
     */
    public function getForUser(CategoryDto $categoryDto)
    {
        $categoryArs = UserRepository::create()
            ->getCategories($categoryDto->getUserId());

        $resultCategoryDtoArray = [];

        foreach ($categoryArs as $categoryAr) {
            $resultCategoryDtoArray[] = CategoryActiveRecordToDtoTranslator::translate($categoryAr);
        }

        return $resultCategoryDtoArray;
    }

    /**
     * @param CategoryDto $categoryDto
     * @param Category $categoryAr
     */
    protected function fillModel(CategoryDto $categoryDto, Category $categoryAr)
    {
        $categoryAr->user_id = $categoryDto->getUserId();

        if ($categoryDto->getName()) {
            $categoryAr->name = $categoryDto->getName();
        }
        if ($categoryDto->getIsIncome() !== null) {
            $categoryAr->is_income = $categoryDto->getIsIncome();
        }
    }
}