<?php

namespace v1\responses;

use common\dto\CategoryDto;
use common\responses\BaseResponse;

class CategoryInfoResponse extends BaseResponse
{
    /**
     * @var CategoryDto
     */
    private $categoryDto;

    /**
     * @param CategoryDto $categoryDto
     * @return $this
     */
    public function setCategoryDto(CategoryDto $categoryDto)
    {
        $this->categoryDto = $categoryDto;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return [
            'id'            => $this->categoryDto->getId(),
            'user_id'       => $this->categoryDto->getUserId(),
            'name'          => $this->categoryDto->getName(),
            'is_income'     => $this->categoryDto->getIsIncome()
        ];
    }
}