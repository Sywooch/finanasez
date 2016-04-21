<?php

namespace common\forms;

use common\classes\TimeService;
use common\vo\SpendTransactionTypeVo;
use Yii;

class StatDashboardForm extends Form
{
    public function process()
    {
        return $this->executeQuery();
    }

    private function executeQuery()
    {
        $sql = "
                    select
                        coalesce(sum(case when datetime_local >=  :today_start           and datetime_local <= :today_end          then amount else 0 end), 0) as today_spend,
                        coalesce(sum(case when datetime_local >=  :yesterday_start       and datetime_local <= :yesterday_end      then amount else 0 end), 0) as yesterday_spend,
                        coalesce(sum(case when datetime_local >=  :current_week_start    and datetime_local <= :current_week_end   then amount else 0 end), 0) as current_week_spend,
                        coalesce(sum(case when datetime_local >=  :previous_week_start   and datetime_local <= :previous_week_end  then amount else 0 end), 0) as previous_week_spend,
                        coalesce(sum(case when datetime_local >=  :current_month_start   and datetime_local <= :current_month_end  then amount else 0 end), 0) as current_month_spend,
                        coalesce(sum(case when datetime_local >=  :previous_month_start  and datetime_local <= :previous_month_end then amount else 0 end), 0) as previous_month_spend
                    from transaction
                    where user_id = :user_id and type = :type
        ";

        $params = [
            ':type'                 => (string) (new SpendTransactionTypeVo()),
            ':user_id'              => $this->getUser()->getId(),
            ':today_start'          => TimeService::create()->getBeginOfDay(),
            ':today_end'            => TimeService::create()->getEndOfDay(),
            ':yesterday_start'      => TimeService::create()->getBeginOfYesterday(),
            ':yesterday_end'        => TimeService::create()->getBeginOfDay(),
            ':previous_week_start'  => TimeService::create()->getBeginOfLastWeek(),
            ':previous_week_end'    => TimeService::create()->getBeginOfCurrentWeek(),
            ':current_week_start'   => TimeService::create()->getBeginOfCurrentWeek(),
            ':current_week_end'     => TimeService::create()->getEndOfCurrentWeek(),
            ':previous_month_start' => TimeService::create()->getBeginOfLastMonth(),
            ':previous_month_end'   => TimeService::create()->getBeginOfCurrentMonth(),
            ':current_month_start'  => TimeService::create()->getBeginOfCurrentMonth(),
            ':current_month_end'    => TimeService::create()->getEndOfCurrentMonth(),
        ];

        $result = Yii::$app->db->createCommand($sql, $params)->queryOne();

        return $result;
    }

}