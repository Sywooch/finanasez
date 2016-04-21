<?php

namespace v1\requests;

use common\forms\CategoryUpdateForm;
use v1\responses\CategoryInfoResponse;
use Yii;

class CategoryUpdateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new CategoryUpdateForm());
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