<?php

namespace v1\requests;

use common\forms\TransactionUpdateForm;
use v1\responses\TransactionInfoResponse;
use Yii;

class TransactionUpdateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new TransactionUpdateForm());
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