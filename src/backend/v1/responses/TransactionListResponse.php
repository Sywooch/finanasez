<?php

namespace v1\responses;

use common\responses\BaseResponse;

class TransactionListResponse extends BaseResponse
{
    /**
     * @var \common\dto\TransactionDto[]
     */
    private $transactionDtoArray = [];

    /**
     * @param \common\dto\TransactionDto[] $transactionDtoArray
     * @return $this
     */
    public function setTransactionDtoArray(array $transactionDtoArray)
    {
        $this->transactionDtoArray = $transactionDtoArray;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $response = [];

        foreach ($this->transactionDtoArray as $transactionDto) {
            $response[$transactionDto->getId()] = TransactionInfoResponse::create()
                ->setTransactionDto($transactionDto)
                ->getResponse();
        }

        return $response;
    }
}