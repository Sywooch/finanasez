<?php

namespace v1\requests;

use common\forms\TransactionListForm;
use v1\responses\TransactionListResponse;
use Yii;

class TransactionListRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new TransactionListForm());
    }

    protected function safeProcess()
    {
        $transactionDtoArray = $this->form->process();

        $response = TransactionListResponse::create()
            ->setTransactionDtoArray($transactionDtoArray)
            ->getResponse();

        return $response;
    }
}