<?php

namespace v1\responses;

use common\responses\BaseResponse;

class StatByCategoryResponse extends BaseResponse
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
            $result[$row['id']] = [
                'id'           => $row['id'],
                'name'         => $row['name'],
                'is_income'    => $row['is_income'],
                'sum'          => $row['sum'],
            ];
        }

        return $result;
    }
}