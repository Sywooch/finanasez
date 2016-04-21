<?php

namespace v1\responses;

use common\dto\BillDto;
use common\responses\BaseResponse;

class BillInfoResponse extends BaseResponse
{
    /**
     * @var BillDto
     */
    private $billDto;

    /**
     * @param BillDto $billDto
     * @return $this
     */
    public function setBillDto(BillDto $billDto)
    {
        $this->billDto = $billDto;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return [
            'id'            => $this->billDto->getId(),
            'user_id'       => $this->billDto->getUserId(),
            'name'          => $this->billDto->getName(),
            'balance'       => $this->billDto->getBalance()
        ];
    }
}