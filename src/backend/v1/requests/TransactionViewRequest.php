<?php

namespace v1\requests;

use common\forms\TransactionViewForm;
use v1\responses\TransactionInfoResponse;
use Yii;

/**
 * @property TransactionViewForm $form
 */
class TransactionViewRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new TransactionViewForm());
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