<?php

namespace v1\requests;

use common\forms\StatByCategoryForm;
use v1\responses\StatByCategoryResponse;
use Yii;

class StatByCategoryRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new StatByCategoryForm());
    }

    protected function safeProcess()
    {
        $data =  $this->form->process();

        $response = StatByCategoryResponse::create()
            ->setData($data)
            ->getResponse();

        return $response;
    }
}