<?php

namespace common\forms;

use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use Yii;

class StatByMonthForm extends Form
{
    public $bill_ids;

    public function rules()
    {
        return [
            ['bill_ids', 'each', 'rule' => ['uuid']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'bill_ids' => 'Фильтр по счетам'
        ];
    }


    public function process()
    {
        return $this->executeQuery();
    }

    private function executeQuery()
    {
        $sqlParams = [
            ':user_id'              => $this->getUser()->getId(),
            ':type_spend'           => (string) (new SpendTransactionTypeVo()),
            ':type_income'          => (string) (new IncomeTransactionTypeVo()),
        ];

        if (is_array($this->bill_ids) && !empty($this->bill_ids)) {
            $billCondition = " AND bill_id IN ('" . implode("','", $this->bill_ids) . "')";
        } else {
            $billCondition = '';
        }

        $sql = "
            select
                date_trunc('month', datetime_local) as month,
                sum(case when type = :type_spend  then amount else 0 end) as spend_sum,
                sum(case when type = :type_income then amount else 0 end) as income_sum
            from transaction
            where
                user_id = :user_id
                {$billCondition}
            group by date_trunc('month', datetime_local)
            having sum(amount) > 0
            order by month asc
        ";

        $result = Yii::$app->db->createCommand($sql, $sqlParams)->queryAll();

        return $result;
    }
}