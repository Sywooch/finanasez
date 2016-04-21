<?php

namespace v1\responses;

use common\responses\BaseResponse;

class BillListResponse extends BaseResponse
{
    /**
     * @var \common\dto\BillDto[]
     */
    private $billDtoArray = [];

    /**
     * @param \common\dto\BillDto[] $billDtoArray
     * @return $this
     */
    public function setBillDtoArray(array $billDtoArray)
    {
        $this->billDtoArray = $billDtoArray;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $response = [];

        foreach ($this->billDtoArray as $billDto) {
            $response[$billDto->getId()] = BillInfoResponse::create()
                ->setBillDto($billDto)
                ->getResponse();
        }

        return $response;
    }
}