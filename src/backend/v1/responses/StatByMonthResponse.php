<?php

namespace v1\responses;

use common\responses\BaseResponse;

class StatByMonthResponse extends BaseResponse
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getResponse()
    {
        $result = [];

        foreach ($this->data as $row) {
            $result[] = [
                'month'           => $row['month'],
                'spend_sum'       => $row['spend_sum'],
                'income_sum'      => $row['income_sum'],
            ];
        }

        return $result;
    }
}