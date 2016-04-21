<?php

namespace v1\requests;

use common\forms\CategoryViewForm;
use v1\responses\CategoryInfoResponse;
use Yii;

class CategoryViewRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new CategoryViewForm());
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