<?php

namespace v1\requests;

use common\forms\CategoryCreateForm;
use v1\responses\CategoryInfoResponse;
use Yii;

class CategoryCreateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new CategoryCreateForm());
    }

    protected function safeProcess()
    {
        $categoryDto = $this->form->process();

        $response = CategoryInfoResponse::create()
            ->setCategoryDto($categoryDto)
            ->getResponse();

        return $response;
    }
}