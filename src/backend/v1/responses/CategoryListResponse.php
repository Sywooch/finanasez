<?php

namespace v1\responses;

use common\responses\BaseResponse;

class CategoryListResponse extends BaseResponse
{
    /**
     * @var \common\dto\CategoryDto[]
     */
    private $categoryDtoArray = [];

    /**
     * @param \common\dto\CategoryDto[] $categoryDtoArray
     * @return $this
     */
    public function setCategoryDtoArray(array $categoryDtoArray)
    {
        $this->categoryDtoArray = $categoryDtoArray;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $response = [];

        foreach ($this->categoryDtoArray as $categoryDto) {
            $response[$categoryDto->getId()] = CategoryInfoResponse::create()
                ->setCategoryDto($categoryDto)
                ->getResponse();
        }

        return $response;
    }
}