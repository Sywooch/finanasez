<?php

namespace v1\requests;

use common\forms\TransactionCreateForm;
use v1\responses\TransactionInfoResponse;
use Yii;

class TransactionCreateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new TransactionCreateForm());
    }

    protected function safeProcess()
    {
        $transactionDto = $this->form->process();

        $response = TransactionInfoResponse::create()
            ->setTransactionDto($transactionDto)
            ->getResponse();

        return $response;
    }
}