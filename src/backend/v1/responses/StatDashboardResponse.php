<?php

namespace v1\responses;

use common\responses\BaseResponse;

class StatDashboardResponse extends BaseResponse
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
        return [
            'today_spend'           => $this->data['today_spend'],
            'yesterday_spend'       => $this->data['yesterday_spend'],
            'current_week_spend'    => $this->data['current_week_spend'],
            'previous_week_spend'   => $this->data['previous_week_spend'],
            'current_month_spend'   => $this->data['current_month_spend'],
            'previous_month_spend'  => $this->data['previous_month_spend'],
        ];
    }
}