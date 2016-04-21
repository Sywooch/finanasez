<?php

namespace v1\requests;

use common\forms\CategoryListForm;
use v1\responses\CategoryListResponse;
use Yii;

class CategoryListRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new CategoryListForm());
    }

    protected function safeProcess()
    {
        $categoryDtoArray = $this->form->process();

        $result = CategoryListResponse::create()
            ->setCategoryDtoArray($categoryDtoArray)
            ->getResponse();

        return $result;
    }
}